import React, { useState, useEffect, useRef, useCallback, useMemo } from 'react';
import { createRoot } from 'react-dom/client';
import { Stage, Layer, Rect, Text, Image } from 'react-konva';
import axios from 'axios';

const MapViewer = () => {
    const { id: mapId, rows, cols, background_image, type: mapType } = window.mapData;

    console.log("MapViewer: Initial window.mapData", window.mapData); // Debug log

    const containerRef = useRef(null);
    const stageRef = useRef(null);
    const [containerWidth, setContainerWidth] = useState(0);

    const [cells, setCells] = useState({}); // Use object for faster lookups
    const [pagination, setPagination] = useState({ currentPage: 1, hasMore: true, isLoading: false, perPage: 0, total: 0 });
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedCell, setSelectedCell] = useState(null);
    const [tooltip, setTooltip] = useState({ visible: false, x: 0, y: 0, text: '' });
    const [hazardSummary, setHazardSummary] = useState('');

    const [stagePos, setStagePos] = useState({ x: 0, y: 0 });
    const [stageScale, setStageScale] = useState(1);
    const [visibleCellRange, setVisibleCellRange] = useState({ startRow: 0, endRow: 0, startCol: 0, endCol: 0 });

    const [searchTerm, setSearchTerm] = useState('');
    const [error, setError] = useState(null);
    const [backgroundImage, setBackgroundImage] = useState(null);

    // Draggable Modal State
    const [modalPosition, setModalPosition] = useState({ x: 0, y: 0 });
    const isDraggingModal = useRef(false);
    const dragStart = useRef({ x: 0, y: 0 });
    const initialModalPos = useRef({ x: 0, y: 0 });

    // Effect for measuring container width
    useEffect(() => {
        const checkSize = () => {
            if (containerRef.current && containerRef.current.offsetWidth !== containerWidth) {
                setContainerWidth(containerRef.current.offsetWidth);
            }
        };
        checkSize();
        window.addEventListener('resize', checkSize);
        return () => window.removeEventListener('resize', checkSize);
    }, [containerWidth]);

    // Effect to load background image
    useEffect(() => {
        if (background_image) {
            const img = new window.Image();
            img.src = `/storage/${background_image}`;
            img.onload = () => setBackgroundImage(img);
        }
    }, [background_image]);

    const fetchCells = useCallback((page) => {
        if (pagination.isLoading) return;

        setPagination(p => ({ ...p, isLoading: true }));
        axios.get(`/api/maps/${mapId}/cells?page=${page}`)
            .then(response => {
                const { data, current_page, last_page, per_page, total } = response.data;
                console.log("MapViewer: API Response for cells", response.data); // Debug log

                const newCells = data.reduce((acc, cell) => {
                    acc[`${cell.row_index}_${cell.col_index}`] = cell;
                    return acc;
                }, {});

                setCells(prevCells => ({ ...prevCells, ...newCells }));
                setPagination(p => ({
                    ...p,
                    currentPage: current_page,
                    hasMore: current_page < last_page,
                    isLoading: false,
                    perPage: per_page,
                    total: total,
                }));
                setError(null);
            })
            .catch(err => {
                console.error("MapViewer: Error fetching cells:", err); // Debug log
                setError("Failed to load map cells. " + (err.response?.data?.message || err.message));
                setPagination(p => ({ ...p, isLoading: false }));
            });
    }, [mapId, pagination.isLoading]);

    // Initial fetch
    useEffect(() => {
        fetchCells(1);
    }, [mapId]);

    // Polling mechanism for periodic updates
    useEffect(() => {
        const pollingInterval = setInterval(() => {
            console.log("MapViewer: Polling for updated cells..."); // Debug log
            fetchCells(1); // Fetch the first page of cells to refresh
        }, 30000); // Poll every 30 seconds

        return () => {
            console.log("MapViewer: Clearing polling interval."); // Debug log
            clearInterval(pollingInterval);
        };
    }, [fetchCells]);

    // Calculate stage and cell dimensions
    let stageWidth = containerWidth > 0 ? containerWidth * 0.75 : 0; // Main stage takes 75%
    let minimapWidth = containerWidth > 0 ? containerWidth * 0.20 : 0; // Minimap takes 20%
    let stageHeight = 0;
    if (stageWidth > 0) {
        if (backgroundImage) {
            const aspectRatio = backgroundImage.naturalWidth / backgroundImage.naturalHeight;
            stageHeight = stageWidth / aspectRatio;
        } else {
            stageHeight = 500; // Fallback height
        }
    }
    const cellWidth = stageWidth > 0 ? stageWidth / cols : 0;
    const cellHeight = stageHeight > 0 ? stageHeight / rows : 0;
    const minimapScale = minimapWidth / stageWidth;

    console.log("MapViewer: Dimensions - stageWidth:", stageWidth, "stageHeight:", stageHeight, "cellWidth:", cellWidth, "cellHeight:", cellHeight); // Debug log
    console.log("MapViewer: Map Data - rows:", rows, "cols:", cols); // Debug log

    // Viewport culling effect
    useEffect(() => {
        if (!stageWidth || !stageHeight) return;

        const scale = stageScale;
        const visibleWidth = stageWidth / scale;
        const visibleHeight = stageHeight / scale;

        const startX = -stagePos.x / scale;
        const startY = -stagePos.y / scale;

        const startCol = Math.max(0, Math.floor(startX / cellWidth));
        const endCol = Math.min(cols - 1, Math.ceil((startX + visibleWidth) / cellWidth));
        const startRow = Math.max(0, Math.floor(startY / cellHeight));
        const endRow = Math.min(rows - 1, Math.ceil((startY + visibleHeight) / cellHeight));

        setVisibleCellRange({ startRow, endRow, startCol, endCol });

    }, [stagePos, stageScale, stageWidth, stageHeight, rows, cols, cellWidth, cellHeight]);

    // Infinite scroll
    useEffect(() => {
        const { endRow } = visibleCellRange;
        if (pagination.hasMore && !pagination.isLoading) {
            const totalRowsLoaded = Math.ceil(Object.keys(cells).length / cols);
            if (endRow >= totalRowsLoaded - 5) { // Fetch when we are close to the edge
                fetchCells(pagination.currentPage + 1);
            }
        }
    }, [visibleCellRange, cells, pagination, fetchCells, cols]);


    const getCellData = (rowIndex, colIndex) => {
        return cells[`${rowIndex}_${colIndex}`];
    };

    // Modal drag handlers
    const handleModalMouseDown = (e) => {
        isDraggingModal.current = true;
        dragStart.current = { x: e.clientX, y: e.clientY };
        initialModalPos.current = { ...modalPosition };
        document.addEventListener('mousemove', handleModalMouseMove);
        document.addEventListener('mouseup', handleModalMouseUp);
        e.preventDefault();
    };

    const handleModalMouseMove = useCallback((e) => {
        if (!isDraggingModal.current) return;
        const deltaX = e.clientX - dragStart.current.x;
        const deltaY = e.clientY - dragStart.current.y;
        setModalPosition({
            x: initialModalPos.current.x + deltaX,
            y: initialModalPos.current.y + deltaY
        });
    }, []);

    const handleModalMouseUp = useCallback(() => {
        isDraggingModal.current = false;
        document.removeEventListener('mousemove', handleModalMouseMove);
        document.removeEventListener('mouseup', handleModalMouseUp);
    }, [handleModalMouseMove]);

    useEffect(() => {
        if (!isModalOpen) {
            setModalPosition({ x: 0, y: 0 });
            setHazardSummary('');
        }
    }, [isModalOpen]);

    const fetchHazardSummary = async (cellId) => {
        try {
            const response = await axios.get(`/api/cells/${cellId}/hazard-summary`);
            setHazardSummary(response.data.summary || 'Tidak ada laporan bahaya.');
        } catch (error) {
            console.error('Error fetching hazard summary:', error);
            setHazardSummary('Gagal memuat ringkasan.');
        }
    };

    const handleCellClick = (rowIndex, colIndex) => {
        const cellData = getCellData(rowIndex, colIndex);
        if (cellData) {
            // Check for linked building map in metadata (for Pabrik maps)
            const linkedBuildingId = cellData.metadata?.gedung_map_id || cellData.building_id;

            if (linkedBuildingId) {
                // Redirect to the detailed building map
                window.location.href = `/karyawan/maps/${linkedBuildingId}`;
            } else {
                setSelectedCell(cellData);
                setIsModalOpen(true);
                if (cellData.id) {
                    fetchHazardSummary(cellData.id);
                }
            }
        }
    };

    const handleStageClick = (e) => {
        if (e.target === e.target.getStage()) {
            const stage = e.target.getStage();
            const transform = stage.getAbsoluteTransform().copy().invert();
            const pos = stage.getPointerPosition();
            const transformedPos = transform.point(pos);

            const row = Math.floor(transformedPos.y / cellHeight);
            const col = Math.floor(transformedPos.x / cellWidth);
            if (row >= 0 && row < rows && col >= 0 && col < cols) {
                handleCellClick(row, col);
            }
        }
    }

    const handleWheel = (e) => {
        e.evt.preventDefault();
        const scaleBy = 1.1;
        const stage = e.target.getStage();
        const oldScale = stage.scaleX();
        const pointer = stage.getPointerPosition();

        const mousePointTo = {
            x: (pointer.x - stage.x()) / oldScale,
            y: (pointer.y - stage.y()) / oldScale,
        };

        const newScale = e.evt.deltaY > 0 ? oldScale / scaleBy : oldScale * scaleBy;

        setStageScale(newScale);
        setStagePos({
            x: pointer.x - mousePointTo.x * newScale,
            y: pointer.y - mousePointTo.y * newScale,
        });
    };

    // Client-side search logic
    const filteredCells = useMemo(() => {
        if (!searchTerm) return cells;
        const lowercasedTerm = searchTerm.toLowerCase();

        // Cari ID gedung yang namanya cocok dengan kata kunci
        const matchingGedungIds = (window.gedungMaps || [])
            .filter(g => g.name.toLowerCase().includes(lowercasedTerm))
            .map(g => g.id.toString());

        return Object.values(cells).filter(cell => {
            // 1. Cek kecocokan lokasi standar (jika ada)
            const matchLocation = (cell.location?.location_id_string && cell.location.location_id_string.toLowerCase().includes(lowercasedTerm)) ||
                (cell.location?.name && cell.location.name.toLowerCase().includes(lowercasedTerm));

            if (matchLocation) return true;

            // 2. Cek kecocokan nama gedung (metadata gedung_map_id pada Pabrik Map)
            const gedungId = cell.metadata?.gedung_map_id;
            if (gedungId && matchingGedungIds.includes(gedungId.toString())) {
                return true;
            }

            return false;
        }).reduce((acc, cell) => {
            acc[`${cell.row_index}_${cell.col_index}`] = cell;
            return acc;
        }, {});
    }, [searchTerm, cells]);


    const gridElements = [];
    if (stageWidth > 0 && stageHeight > 0) {
        const sourceCells = searchTerm ? filteredCells : cells;
        console.log("MapViewer: Generating grid elements. Number of source cells:", Object.keys(sourceCells).length); // Debug log
        for (let i = visibleCellRange.startRow; i <= visibleCellRange.endRow; i++) {
            for (let j = visibleCellRange.startCol; j <= visibleCellRange.endCol; j++) {
                const cellData = sourceCells[`${i}_${j}`];

                // Jika sedang mencari (searchTerm), sel yang cocok harus terlihat meskipun risk_scorenya 0.
                const isMatch = searchTerm && cellData;
                const hasRisk = cellData && cellData.risk_score > 0;

                // Tentukan warna: gunakan zone_color jika ada risiko, atau abu-abu terang jika hanya match pencarian.
                const fillColor = hasRisk
                    ? (cellData.zone_color || '#9ca3af')
                    : (isMatch ? '#e5e7eb' : 'transparent');

                // Hilangkan garis tepi merah agar peta bersih
                const strokeColor = 'transparent';

                gridElements.push(
                    <Rect
                        key={`rect-${i}-${j}`}
                        x={j * cellWidth}
                        y={i * cellHeight}
                        width={cellWidth}
                        height={cellHeight}
                        fill={fillColor}
                        stroke={strokeColor}
                        strokeWidth={0}
                        opacity={cellData ? 0.7 : 0}
                        onClick={() => handleCellClick(i, j)}
                        onMouseEnter={(e) => {
                            const stage = e.target.getStage();
                            if (cellData?.metadata?.gedung_map_id) {
                                stage.container().style.cursor = 'pointer';
                                const gedungMaps = window.gedungMaps || [];
                                const linkedGedung = gedungMaps.find(g => g.id == cellData.metadata.gedung_map_id);
                                if (linkedGedung) {
                                    const pointerPos = stage.getPointerPosition();
                                    const tooltipX = (pointerPos.x / stage.scaleX()) - (stage.x() / stage.scaleX()) + 10;
                                    const tooltipY = (pointerPos.y / stage.scaleY()) - (stage.y() / stage.scaleY()) + 10;

                                    setTooltip({
                                        visible: true,
                                        x: tooltipX,
                                        y: tooltipY,
                                        text: `Gedung: ${linkedGedung.name}`
                                    });
                                }
                            } else if (cellData) {
                                stage.container().style.cursor = 'pointer';
                            }
                        }}
                        onMouseLeave={(e) => {
                            const stage = e.target.getStage();
                            stage.container().style.cursor = 'default';
                            setTooltip({ ...tooltip, visible: false });
                        }}
                    />
                );
            }
        }
    }
    console.log("MapViewer: Total grid elements generated:", gridElements.length); // Debug log


    return (
        <div className="flex" ref={containerRef}>
            <div className="flex-1 mr-4">
                {error && (
                    <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong className="font-bold">Error!</strong>
                        <span className="block sm:inline ml-2">{error}</span>
                    </div>
                )}
                <div className="mb-4">
                    <input
                        type="text"
                        placeholder="Cari Nama Gedung atau ID Area..."
                        value={searchTerm}
                        onChange={(e) => setSearchTerm(e.target.value)}
                        className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    />
                </div>
                {stageWidth > 0 && (
                    <Stage
                        ref={stageRef}
                        width={stageWidth}
                        height={stageHeight}
                        scaleX={stageScale}
                        scaleY={stageScale}
                        x={stagePos.x}
                        y={stagePos.y}
                        onWheel={handleWheel}
                        onClick={handleStageClick}
                        draggable
                        onDragEnd={(e) => setStagePos(e.target.position())}
                        className="bg-gray-50 border border-gray-300 rounded-lg shadow-inner"
                    >
                        <Layer>
                            {backgroundImage && <Image image={backgroundImage} width={stageWidth} height={stageHeight} />}
                        </Layer>
                        <Layer>{gridElements}</Layer>
                        <Layer>
                            {tooltip.visible && (
                                <>
                                    <Rect
                                        x={tooltip.x}
                                        y={tooltip.y}
                                        width={tooltip.text.length * 8 + 20}
                                        height={24}
                                        fill="black"
                                        opacity={0.75}
                                        cornerRadius={5}
                                    />
                                    <Text
                                        x={tooltip.x + 10}
                                        y={tooltip.y + 5}
                                        text={tooltip.text}
                                        fontSize={12}
                                        fill="white"
                                    />
                                </>
                            )}
                        </Layer>
                    </Stage>
                )}


                {/* Simple & Clean Risk Zone Legend (Synced with SHE view) */}
                <div className="mt-6 p-4 bg-white rounded-xl border border-gray-100 shadow-sm">
                    <div className="flex items-center gap-2 mb-4">
                        <h4 className="font-bold text-gray-700 text-sm uppercase tracking-wider">Risk Zone Legend</h4>
                        <div className="h-px flex-1 bg-gray-100"></div>
                    </div>

                    <div className="flex flex-wrap items-center gap-x-8 gap-y-3 px-2">
                        {[
                            { range: '1–5', label: 'Low Risk', color: '#10b981' },
                            { range: '6–10', label: 'Medium Risk', color: '#f59e0b' },
                            { range: '11–15', label: 'Medium-High Risk', color: '#ef4444' },
                            { range: '16–20', label: 'High Risk', color: '#f43f5e' },
                            { range: '21–25', label: 'Extreme Risk', color: '#ff1a1a' },
                        ].map((item, idx) => (
                            <div key={idx} className="flex items-center gap-3">
                                <div className="w-3 h-3 rounded-full shadow-sm" style={{ backgroundColor: item.color }}></div>
                                <div className="flex items-baseline gap-1.5">
                                    <span className="text-sm font-bold text-gray-800">{item.range}</span>
                                    <span className="text-xs text-gray-500">({item.label})</span>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>

            <div style={{ width: minimapWidth }}>
                <div className="p-4 bg-white rounded-lg shadow mb-4">
                    <h4 className="font-bold text-gray-800 mb-2">Mini Map</h4>
                    {stageWidth > 0 && <Stage
                        width={minimapWidth}
                        height={stageHeight * minimapScale}
                        className="bg-gray-50 border border-gray-300 rounded-lg shadow-inner"
                    >
                        <Layer scaleX={minimapScale} scaleY={minimapScale}>
                            {backgroundImage && <Image image={backgroundImage} width={stageWidth} height={stageHeight} />}
                            {Object.values(cells).map(cell => (
                                <Rect
                                    key={`minimap-rect-${cell.row_index}-${cell.col_index}`}
                                    x={cell.col_index * cellWidth}
                                    y={cell.row_index * cellHeight}
                                    width={cellWidth}
                                    height={cellHeight}
                                    fill={(cell.risk_score > 0) ? (cell.zone_color || '#9ca3af') : 'transparent'}
                                    opacity={(cell.risk_score > 0) ? 0.7 : 0}
                                />
                            ))}
                            <Rect
                                x={-stagePos.x / stageScale}
                                y={-stagePos.y / stageScale}
                                width={stageWidth / stageScale}
                                height={stageHeight / stageScale}
                                stroke="blue"
                                strokeWidth={5 / minimapScale}
                            />
                        </Layer>
                    </Stage>}
                </div>
            </div>

            {isModalOpen && selectedCell && (
                <div className="fixed inset-0 bg-black/30 z-50 flex justify-center items-center p-4">
                    <div
                        className="bg-white rounded-xl shadow-2xl max-w-sm w-full border border-gray-200"
                        style={{ transform: `translate(${modalPosition.x}px, ${modalPosition.y}px)` }}
                    >
                        <div
                            className="px-4 py-3 border-b cursor-move select-none bg-gray-50 flex items-center justify-between"
                            onMouseDown={handleModalMouseDown}
                        >
                            <div>
                                <h3 className="text-sm font-bold text-gray-800">Detail Lokasi</h3>
                                <p className="text-xs text-gray-500">Cell ({selectedCell.row_index}, {selectedCell.col_index})</p>
                            </div>
                            <button
                                onClick={() => setIsModalOpen(false)}
                                className="text-gray-400 hover:text-gray-600"
                            >
                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div className="p-4 space-y-3">
                            {/* Location */}
                            <div>
                                <p className="text-xs font-semibold text-gray-500 mb-1">LOKASI</p>
                                <p className="text-sm font-bold text-gray-900">
                                    {selectedCell.location ? selectedCell.location.name : 'Tidak ada lokasi'}
                                </p>
                                {selectedCell.location && (
                                    <p className="text-xs text-gray-600">{selectedCell.location.location_id_string} • {selectedCell.location.type}</p>
                                )}
                            </div>

                            {/* Risk */}
                            <div className="flex items-center justify-between pt-2 border-t">
                                <div>
                                    <p className="text-xs font-semibold text-gray-500 mb-1">RISIKO</p>
                                    <p className="text-xl font-bold text-gray-900">{selectedCell.risk_score || '0'}</p>
                                </div>
                                <div
                                    className="w-10 h-10 rounded-lg border-2 border-gray-300"
                                    style={{ backgroundColor: selectedCell.zone_color || '#e5e7eb' }}
                                ></div>
                            </div>

                            {/* Hazard Summary */}
                            {hazardSummary && hazardSummary !== 'Tidak ada laporan bahaya aktif.' && (
                                <div className="bg-amber-50 border-l-4 border-amber-400 p-3 rounded-r">
                                    <p className="text-xs font-bold text-amber-800 mb-1">POTENSI BAHAYA</p>
                                    <p className="text-xs text-gray-700">{hazardSummary}</p>
                                </div>
                            )}
                        </div>

                        <div className="px-4 py-3 bg-gray-50 flex gap-2 border-t">
                            <button
                                onClick={() => setIsModalOpen(false)}
                                className="flex-1 px-3 py-2 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
                            >
                                Tutup
                            </button>
                            <a
                                href={`/karyawan/hazards/create?map_id=${mapId}&cell_id=${selectedCell.id}&location_id=${selectedCell.location_id || ''}`}
                                className="flex-1 px-3 py-2 text-xs font-bold text-white bg-red-600 rounded-lg hover:bg-red-700 text-center"
                            >
                                Laporkan Bahaya
                            </a>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
};

export default MapViewer;

const element = document.getElementById('map-viewer');
if (element) {
    createRoot(element).render(<MapViewer />);
}
