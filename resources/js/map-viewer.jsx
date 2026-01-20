import React, { useState, useEffect, useRef, useCallback, useMemo } from 'react';
import { createRoot } from 'react-dom/client';
import { Stage, Layer, Rect, Text, Image } from 'react-konva';
import axios from 'axios';

const MapViewer = () => {
    const { id: mapId, rows, cols, background_image } = window.mapData;

    console.log("MapViewer: Initial window.mapData", window.mapData); // Debug log

    const containerRef = useRef(null);
    const stageRef = useRef(null);
    const [containerWidth, setContainerWidth] = useState(0);

    const [cells, setCells] = useState({}); // Use object for faster lookups
    const [pagination, setPagination] = useState({ currentPage: 1, hasMore: true, isLoading: false, perPage: 0, total: 0 });
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedCell, setSelectedCell] = useState(null);
    const [tooltip, setTooltip] = useState({ visible: false, x: 0, y: 0, text: '' });

    const [stagePos, setStagePos] = useState({ x: 0, y: 0 });
    const [stageScale, setStageScale] = useState(1);
    const [visibleCellRange, setVisibleCellRange] = useState({ startRow: 0, endRow: 0, startCol: 0, endCol: 0 });

    const [searchTerm, setSearchTerm] = useState('');
    const [error, setError] = useState(null);
    const [backgroundImage, setBackgroundImage] = useState(null);

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
        return Object.values(cells).filter(cell =>
            (cell.location?.location_id_string && cell.location.location_id_string.toLowerCase().includes(lowercasedTerm)) ||
            (cell.location?.name && cell.location.name.toLowerCase().includes(lowercasedTerm))
        ).reduce((acc, cell) => {
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
                const fillColor = cellData?.zone_color || 'white';

                gridElements.push(
                    <Rect
                        key={`rect-${i}-${j}`}
                        x={j * cellWidth}
                        y={i * cellHeight}
                        width={cellWidth}
                        height={cellHeight}
                        fill={fillColor}
                        stroke={cellData?.metadata?.gedung_map_id ? '#dc2626' : 'black'}
                        strokeWidth={cellData?.metadata?.gedung_map_id ? 1.5 / stageScale : 0.5 / stageScale}
                        opacity={cellData ? 0.7 : 0.5}
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
                        placeholder="Search by Area ID or Name (in loaded cells)"
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


                <div className="mt-4 p-4 bg-white rounded-lg shadow">
                    <h4 className="font-bold text-gray-800 mb-2">Risk Zone Legend</h4>

                    <div className="flex items-center mb-1">
                        <span
                            className="block w-6 h-4 rounded-sm mr-2 border border-gray-300"
                            style={{ backgroundColor: "#10b981" }}
                        />
                        <span className="text-sm text-gray-700">1–5 (Low Risk)</span>
                    </div>

                    <div className="flex items-center mb-1">
                        <span
                            className="block w-6 h-4 rounded-sm mr-2 border border-gray-300"
                            style={{ backgroundColor: "#f59e0b" }}
                        />
                        <span className="text-sm text-gray-700">6–10 (Medium Risk)</span>
                    </div>

                    <div className="flex items-center mb-1">
                        <span
                            className="block w-6 h-4 rounded-sm mr-2 border border-gray-300"
                            style={{ backgroundColor: "#ef4444" }}
                        />
                        <span className="text-sm text-gray-700">11–15 (Medium-High Risk)</span>
                    </div>

                    <div className="flex items-center mb-1">
                        <span
                            className="block w-6 h-4 rounded-sm mr-2 border border-gray-300"
                            style={{ backgroundColor: "#f43f5e" }}
                        />
                        <span className="text-sm text-gray-700">16–20 (High Risk)</span>
                    </div>

                    <div className="flex items-center">
                        <span
                            className="block w-6 h-4 rounded-sm mr-2 border border-gray-300"
                            style={{ backgroundColor: "#ff1a1a" }}
                        />
                        <span className="text-sm text-gray-700">21–25 (Extreme Risk)</span>
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
                                    fill={cell.zone_color || 'white'}
                                    opacity={0.7}
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
                <div className="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center p-4">
                    <div className="bg-white p-6 rounded-lg shadow-xl max-w-sm w-full">
                        <div className="p-6 border-b">
                            <h3 className="text-2xl font-bold text-gray-800">Cell Details ({selectedCell.row_index},{selectedCell.col_index})</h3>
                        </div>
                        <div className="p-6">
                            <p><strong>Lokasi:</strong> {selectedCell.location ? `${selectedCell.location.name} (${selectedCell.location.location_id_string})` : 'N/A'}</p>
                            <p><strong>Tipe Lokasi:</strong> {selectedCell.location ? selectedCell.location.type : 'N/A'}</p>
                            <hr className="my-4" />
                            <p><strong>Risk Score:</strong> {selectedCell.risk_score}</p>
                            <div style={{ display: 'flex', alignItems: 'center' }}>
                                <strong>Zone Color:</strong>
                                <span style={{ display: 'inline-block', width: '20px', height: '20px', backgroundColor: selectedCell.zone_color, marginLeft: '10px', border: '1px solid black' }}></span>
                            </div>
                        </div>
                        <div className="p-6 bg-gray-50 rounded-b-lg flex justify-end items-center gap-4 border-t">
                            <button onClick={() => setIsModalOpen(false)} className="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">Close</button>
                            <a
                                href={`/karyawan/hazards/create?map_id=${mapId}&cell_id=${selectedCell.id}&location_id=${selectedCell.location_id || ''}`}
                                className="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                            >
                                Laporkan Bahaya Di Sini
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
