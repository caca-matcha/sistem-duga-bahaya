import React, { useState, useEffect, useRef, useCallback } from 'react';
import { createRoot } from 'react-dom/client';
import { Stage, Layer, Rect, Text, Image } from 'react-konva';
import axios from 'axios';

const GridEditor = () => {
    const { id: mapId, rows, cols, background_image, type: mapType, name: mapName } = window.mapData;

    const containerRef = useRef(null);
    const stageRef = useRef(null);
    const [containerWidth, setContainerWidth] = useState(0);

    const [cells, setCells] = useState({}); // Use object for faster lookups
    const [pagination, setPagination] = useState({ currentPage: 1, hasMore: true, isLoading: false, perPage: 0, total: 0 });
    const [selectedCells, setSelectedCells] = useState([]);
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [tooltip, setTooltip] = useState({ visible: false, x: 0, y: 0, text: '' });

    const [selectionRect, setSelectionRect] = useState({ x: 0, y: 0, width: 0, height: 0, visible: false });
    const selectionStarted = useRef(false);
    const isSelecting = useRef(false);

    const [stagePos, setStagePos] = useState({ x: 0, y: 0 });
    const [stageScale, setStageScale] = useState(1);
    const [visibleCellRange, setVisibleCellRange] = useState({ startRow: 0, endRow: 0, startCol: 0, endCol: 0 });

    const [formData, setFormData] = useState({
        location_id: '',
        gedung_map_id: '', // New state for gedung map ID
    });
    const [error, setError] = useState(null);
    const [backgroundImage, setBackgroundImage] = useState(null);
    const [masterLocations, setMasterLocations] = useState([]); // State untuk lokasi master (non-Pabrik map)
    const [gedungMaps, setGedungMaps] = useState([]); // New state for Gedung maps (Pabrik map)

    const [isStageDragging, setIsStageDragging] = useState(false);
    const [deleteConfirmModal, setDeleteConfirmModal] = useState(null);
    const [isUpdating, setIsUpdating] = useState(false);

    useEffect(() => {
        const handleResize = () => {
            if (containerRef.current) {
                setContainerWidth(containerRef.current.offsetWidth);
            }
        };

        handleResize();
        window.addEventListener('resize', handleResize);
        return () => window.removeEventListener('resize', handleResize);
    }, []);

    useEffect(() => {
        if (background_image) {
            const img = new window.Image();
            img.src = background_image.startsWith('http') ? background_image : `/storage/${background_image}`;
            img.onload = () => {
                setBackgroundImage(img);
            };
            img.onerror = () => {
                console.error("Failed to load background image:", img.src);
                setError("Failed to load background image.");
                setBackgroundImage(null); // Fallback on error
            }
        } else {
            setBackgroundImage(null); // Fallback if no image is provided
        }
    }, [background_image]);

    useEffect(() => {
        if (mapType === 'Pabrik' || !mapId) return; // Only fetch if it's NOT a Pabrik map and mapId is available

        // Fetch locations that are specifically mapped to this map
        axios.get(`/api/maps/${mapId}/locations`)
            .then(response => {
                setMasterLocations(response.data);
            })
            .catch(err => {
                console.error("Error fetching mapped locations:", err);
                setError("Failed to load mapped locations.");
            });
    }, [mapId, mapType]);

    // Effect to fetch Gedung maps (for Pabrik maps)
    useEffect(() => {
        if (mapType !== 'Pabrik' || !mapId) return; // Only fetch if it's a Pabrik map and mapId is available

        axios.get(`/api/maps`) // Use the API endpoint to get all Gedung maps (now filtered by API)
            .then(response => {
                // Filter out the current map to prevent self-linking
                const filteredMaps = response.data.filter(m => m.id !== mapId);
                setGedungMaps(filteredMaps);
            })
            .catch(err => {
                console.error("Error fetching Gedung maps:", err);
                setError("Failed to load Gedung maps.");
            });
    }, [mapId, mapType]);

    useEffect(() => {
        if (!isModalOpen) setError(null);
    }, [isModalOpen]);

    const fetchCells = useCallback((page) => {
        if (pagination.isLoading) return;

        setIsUpdating(true); // Set updating state to true
        setPagination(p => ({ ...p, isLoading: true }));
        axios.get(`/api/maps/${mapId}/cells?page=${page}`)
            .then(response => {
                const { data, current_page, last_page, per_page, total } = response.data;

                // Convert array to object for efficient lookup
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
                console.error("Error fetching cells:", err);
                setError("Failed to load map cells. " + (err.response?.data?.message || err.message));
                setPagination(p => ({ ...p, isLoading: false }));
            })
            .finally(() => {
                setIsUpdating(false); // Set updating state to false when fetch completes
            });
    }, [mapId, pagination.isLoading]);

    // Initial fetch
    useEffect(() => {
        fetchCells(1);
    }, [mapId]);

    // Polling mechanism for periodic updates
    useEffect(() => {
        const pollingInterval = setInterval(() => {
            console.log("GridEditor: Polling for updated cells...");
            fetchCells(1); // Fetch the first page of cells to refresh
        }, 3600000); // Poll every 1 hour

        return () => clearInterval(pollingInterval);
    }, [fetchCells]);

    // Listen for custom event to manually refresh
    useEffect(() => {
        const handleRefresh = () => {
            console.log('GridEditor: Manual refresh triggered by custom event.');
            fetchCells(1);
        };

        window.addEventListener('refresh-grid-editor', handleRefresh);

        return () => {
            window.removeEventListener('refresh-grid-editor', handleRefresh);
        };
    }, [fetchCells]);

    // Calculate stage and cell dimensions
    let stageWidth = containerWidth;
    let stageHeight = 0;
    if (containerWidth > 0) {
        if (backgroundImage) {
            const aspectRatio = backgroundImage.naturalWidth / backgroundImage.naturalHeight;
            stageHeight = containerWidth / aspectRatio;
        } else {
            stageHeight = 500; // Fallback height
        }
    }
    const cellWidth = stageWidth > 0 ? stageWidth / cols : 0;
    const cellHeight = stageHeight > 0 ? stageHeight / rows : 0;

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
        const { endRow, endCol } = visibleCellRange;
        const totalCellsLoaded = Object.keys(cells).length;
        // A simple heuristic to check if we need more data: if the user can see the last loaded cells.
        // This can be improved.
        if (pagination.hasMore && !pagination.isLoading) {
            const isAtEdge = endRow >= Math.floor(totalCellsLoaded / cols);
            if (isAtEdge) {
                fetchCells(pagination.currentPage + 1);
            }
        }
    }, [visibleCellRange, cells, pagination, fetchCells, cols]);


    const getCellData = (rowIndex, colIndex) => {
        return cells[`${rowIndex}_${colIndex}`];
    };

    const handleCellClick = (rowIndex, colIndex) => {
        setError(null);

        setSelectedCells([{ row_index: rowIndex, col_index: colIndex }]);

        const currentCell = getCellData(rowIndex, colIndex);
        if (mapType === 'Pabrik') {
            const gedungMapIdFromMetadata = currentCell?.metadata?.gedung_map_id || '';
            setFormData({ gedung_map_id: gedungMapIdFromMetadata });
        } else {
            setFormData({ location_id: currentCell?.location_id || '' });
        }
        setIsModalOpen(true);
    };

    const saveSelection = () => {
        if (selectedCells.length === 0) return;
        setError(null);

        let payload = {
            map_id: mapId,
            cells: selectedCells,
        };

        if (mapType === 'Pabrik') {
            payload = {
                ...payload,
                gedung_map_id: formData.gedung_map_id === '' ? null : formData.gedung_map_id,
            };
        } else {
            payload = {
                ...payload,
                location_id: formData.location_id === '' ? null : formData.location_id,
            };
        }

        axios.post(`/she/api/cells/batch-update`, payload)
            .then(response => {
                const updatedCellsFromServer = response.data; // Server now returns updated cells

                // Update the cells state with the fresh data from the server
                const updatedCellsState = { ...cells };
                updatedCellsFromServer.forEach(cell => {
                    const key = `${cell.row_index}_${cell.col_index}`;
                    updatedCellsState[key] = {
                        ...updatedCellsState[key], // Keep existing data if not overridden
                        ...cell, // Update with fresh data from server (including risk_score, zone_color, metadata)
                        location: cell.location || null, // Ensure location object is updated if present
                        // If mapType is Pabrik, location_id should be null
                        location_id: mapType === 'Pabrik' ? null : cell.location_id,
                    };
                });
                setCells(updatedCellsState);

                setIsModalOpen(false);
                setSelectedCells([]);
            })
            .catch(err => {
                console.error("Error batch saving cells:", err);
                const errorMessage = err.response?.data?.message || err.message;
                setError("Failed to save selection: " + errorMessage);
            });
    };

    const handleDeleteLocation = () => {
        if (selectedCells.length === 0) return;
        setError(null);

        const payload = {
            map_id: mapId,
            cells: selectedCells,
            location_id: null, // Explicitly set location to null
        };

        axios.post(`/she/api/cells/batch-update`, payload)
            .then(response => {
                const updatedCellsFromServer = response.data; // Server returns updated cells

                // Update the cells state
                const updatedCellsState = { ...cells };
                updatedCellsFromServer.forEach(cell => {
                    const key = `${cell.row_index}_${cell.col_index}`;
                    updatedCellsState[key] = {
                        ...updatedCellsState[key],
                        ...cell,
                        location: null,
                        location_id: null,
                    };
                });
                setCells(updatedCellsState);

                setIsModalOpen(false);
                setSelectedCells([]);
            })
            .catch(err => {
                console.error("Error deleting location from cells:", err);
                const errorMessage = err.response?.data?.message || "An unexpected error occurred.";
                setError("Failed to remove location: " + errorMessage);
            });
    };

    const handleDeleteGedungMap = () => {
        if (selectedCells.length === 0) return;
        setError(null);

        const payload = {
            map_id: mapId,
            cells: selectedCells,
            gedung_map_id: null, // Set gedung_map_id to null for Pabrik maps
        };

        axios.post(`/she/api/cells/batch-update`, payload)
            .then(response => {
                const updatedCellsFromServer = response.data;

                // Update the cells state
                const updatedCellsState = { ...cells };
                updatedCellsFromServer.forEach(cell => {
                    const key = `${cell.row_index}_${cell.col_index}`;
                    updatedCellsState[key] = {
                        ...updatedCellsState[key],
                        ...cell,
                        metadata: { ...cell.metadata, gedung_map_id: null }, // Clear gedung_map_id
                    };
                });
                setCells(updatedCellsState);

                setIsModalOpen(false);
                setSelectedCells([]);
            })
            .catch(err => {
                console.error("Error deleting Gedung map from cells:", err);
                const errorMessage = err.response?.data?.message || "An unexpected error occurred.";
                setError("Failed to remove Gedung map: " + errorMessage);
            });
    };

    // ... (mouse handlers need to be updated to account for stage position/scale)

    const handleMouseDown = (e) => {
        console.log('Stage: Mouse Down event triggered.', e.evt); // Debugging
        // If spacebar is pressed, do nothing and let the stage drag
        if (isStageDragging) return;

        // Proceed with selection logic
        e.evt.preventDefault();
        selectionStarted.current = true;
        isSelecting.current = false;
        const pos = e.target.getStage().getPointerPosition();

        // Transform pointer position by inverse of stage transform
        const transform = e.target.getStage().getAbsoluteTransform().copy().invert();
        const transformedPos = transform.point(pos);

        setSelectionRect({ x: transformedPos.x, y: transformedPos.y, width: 0, height: 0, visible: true });
        setSelectedCells([]);
    };

    const handleMouseMove = (e) => {
        if (!selectionStarted.current) return;
        e.evt.preventDefault();
        isSelecting.current = true;
        const pos = e.target.getStage().getPointerPosition();
        const transform = e.target.getStage().getAbsoluteTransform().copy().invert();
        const transformedPos = transform.point(pos);

        setSelectionRect(prevRect => ({
            ...prevRect,
            width: transformedPos.x - prevRect.x,
            height: transformedPos.y - prevRect.y,
        }));
    };

    const handleMouseUp = (e) => {
        selectionStarted.current = false;
        setSelectionRect(prev => ({ ...prev, visible: false }));

        const stage = e.target.getStage();
        const transform = stage.getAbsoluteTransform().copy().invert();
        const pos = stage.getPointerPosition();
        const transformedPos = transform.point(pos);

        if (!isSelecting.current) {
            const row = Math.floor(transformedPos.y / cellHeight);
            const col = Math.floor(transformedPos.x / cellWidth);
            if (row >= 0 && row < rows && col >= 0 && col < cols) {
                handleCellClick(row, col);
            }
            return;
        }
        isSelecting.current = false;

        const newSelectedCells = [];
        const { x, y, width, height } = selectionRect;
        const sx = width > 0 ? x : x + width;
        const sy = height > 0 ? y : y + height;
        const ex = width > 0 ? x + width : x;
        const ey = height > 0 ? y + height : y;

        for (let i = visibleCellRange.startRow; i <= visibleCellRange.endRow; i++) {
            for (let j = visibleCellRange.startCol; j <= visibleCellRange.endCol; j++) {
                const cellX = j * cellWidth;
                const cellY = i * cellHeight;
                if (cellX < ex && cellX + cellWidth > sx && cellY < ey && cellY + cellHeight > sy) {
                    newSelectedCells.push({ row_index: i, col_index: j });
                }
            }
        }

        if (newSelectedCells.length > 0) {
            setSelectedCells(newSelectedCells);
            setFormData({
                location_id: '',
                gedung_map_id: '',
            });
            setIsModalOpen(true);
        }
    };

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

    const getTextColor = (score) => {
        if (score === null || score < 1 || score > 25) {
            return "#FFFFFF"; // White text for gray default background
        }
        if (score <= 10) { // Low to Medium
            return "#1f2937"; // Dark gray/black text
        } else { // Medium-High to Extreme
            return "#FFFFFF"; // White text
        }
    };

    const gridElements = [];
    if (stageWidth > 0 && stageHeight > 0) {
        for (let i = visibleCellRange.startRow; i <= visibleCellRange.endRow; i++) {
            for (let j = visibleCellRange.startCol; j <= visibleCellRange.endCol; j++) {
                const cellData = getCellData(i, j);
                const isSelected = selectedCells.some(c => c.row_index === i && c.col_index === j);

                // For Pabrik maps, only show color if gedung_map_id exists
                let fillColor = 'white';
                if (cellData) {
                    if (mapType === 'Pabrik') {
                        fillColor = cellData.metadata?.gedung_map_id ? (cellData.zone_color || 'white') : 'white';
                    } else {
                        fillColor = cellData.zone_color || 'white';
                    }
                }

                gridElements.push(
                    <Rect
                        key={`rect-${i}-${j}`}
                        x={j * cellWidth}
                        y={i * cellHeight}
                        width={cellWidth}
                        height={cellHeight}
                        fill={fillColor}
                        stroke={isSelected ? '#A594F9' : '#CCC'}
                        strokeWidth={isSelected ? 3 / stageScale : 0.5}
                        opacity={cellData ? (isSelected ? 1 : 0.7) : 0.5}
                        shadowColor={isSelected ? '#A594F9' : undefined}
                        shadowBlur={isSelected ? 10 : 0}
                        shadowOpacity={isSelected ? 0.5 : 0}
                        onMouseEnter={(e) => {
                            if (mapType === 'Pabrik' && cellData?.metadata?.gedung_map_id) {
                                const hoveredGedung = gedungMaps.find(g => g.id === cellData.metadata.gedung_map_id);
                                if (hoveredGedung) {
                                    const stage = e.target.getStage();
                                    const pointerPos = stage.getPointerPosition();
                                    // Adjust for stage position and scale for accurate tooltip placement relative to the viewport
                                    const tooltipX = (pointerPos.x / stage.scaleX()) - (stage.x() / stage.scaleX()) + 10;
                                    const tooltipY = (pointerPos.y / stage.scaleY()) - (stage.y() / stage.scaleY()) + 10;

                                    setTooltip({
                                        visible: true,
                                        x: tooltipX,
                                        y: tooltipY,
                                        text: hoveredGedung.name
                                    });
                                }
                            }
                        }}
                        onMouseLeave={() => {
                            setTooltip({ ...tooltip, visible: false });
                        }}
                    />,
                    <Text
                        key={`text-${i}-${j}`}
                        x={j * cellWidth + 5}
                        y={i * cellHeight + 5}
                        text={(cellData?.risk_score ?? '').toString()}
                        fontSize={14 / stageScale}
                        fill={getTextColor(cellData?.risk_score)} // Use the new function
                        listening={false}
                        visible={cellData && stageScale > 0.5} // Only show text when zoomed in
                    />
                );
            }
        }
    }


    return (
        <div ref={containerRef}>
            <div className="flex items-center justify-between mb-4">
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Grid Editor: {mapName}
                </h2>
                <button
                    type="button"
                    onClick={() => fetchCells(1)}
                    disabled={isUpdating}
                    className={`inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 ${isUpdating ? 'opacity-50 cursor-not-allowed' : 'hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300'
                        }`}
                >
                    {isUpdating ? 'Updating...' : 'Update Map'}
                </button>
            </div>

            {/* Error Display */}
            {error && !isModalOpen && (
                <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong className="font-bold">Error!</strong>
                    <span className="block sm:inline ml-2">{error}</span>
                </div>
            )}
            {stageWidth > 0 && (
                <Stage
                    ref={stageRef}
                    width={stageWidth}
                    height={stageHeight}
                    onMouseDown={handleMouseDown}
                    onMouseMove={handleMouseMove}
                    onMouseUp={handleMouseUp}
                    onWheel={handleWheel}
                    draggable={isStageDragging}
                    x={stagePos.x}
                    y={stagePos.y}
                    scaleX={stageScale} scaleY={stageScale}
                    className="bg-gray-50 border border-gray-300 rounded-lg shadow-inner"
                >
                    <Layer>
                        {backgroundImage ? (
                            <Image
                                image={backgroundImage}
                                width={stageWidth}
                                height={stageHeight}
                            />
                        ) : (
                            <Rect
                                width={stageWidth}
                                height={stageHeight}
                                fill="#f0f0f0" // Light gray fallback background
                            />
                        )}
                    </Layer>
                    <Layer>
                        {gridElements}
                        <Rect
                            x={selectionRect.x}
                            y={selectionRect.y}
                            width={selectionRect.width}
                            height={selectionRect.height}
                            fill="rgba(0,0,255,0.3)"
                            visible={selectionRect.visible}
                        />
                    </Layer>
                    <Layer>
                        {tooltip.visible && (
                            <>
                                <Rect
                                    x={tooltip.x}
                                    y={tooltip.y}
                                    width={tooltip.text.length * 8 + 20} // Estimate width based on text length
                                    height={24} // Height of the tooltip background
                                    fill="black"
                                    opacity={0.75}
                                    cornerRadius={5}
                                />
                                <Text
                                    x={tooltip.x + 10} // Padding for text
                                    y={tooltip.y + 5} // Padding for text
                                    text={tooltip.text}
                                    fontSize={12}
                                    fill="white"
                                />
                            </>
                        )}
                    </Layer>
                </Stage>
            )}

            {/* Legend and other UI elements... */}
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


            {/* Modal remains largely the same */}
            {isModalOpen && (
                <div className="fixed inset-0 z-50 flex justify-center items-center p-4">
                    <div className="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[80vh] overflow-y-auto">
                        <div className="p-6 border-b">
                            <h3 className="text-2xl font-bold text-gray-800">Edit {selectedCells.length} cell(s)</h3>
                        </div>
                        <div className="p-6">
                            {error && (
                                <div className="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                                    <p className="font-bold">Error!</p>
                                    <p>{error}</p>
                                </div>
                            )}

                            <div className="mb-6">
                                <h4 className="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Informasi Lokasi</h4>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {mapType === 'Pabrik' ? (
                                        <div className="md:col-span-2">
                                            <label htmlFor="gedung_map_id" className="block text-sm font-medium text-gray-600">Pilih Gedung</label>
                                            <div className="mt-1 flex items-center gap-2">
                                                <select name="gedung_map_id" id="gedung_map_id" value={formData.gedung_map_id} onChange={(e) => setFormData({ ...formData, gedung_map_id: e.target.value })} className="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                                    <option value="">-- Pilih Gedung --</option>
                                                    {gedungMaps.map(gedung => (
                                                        <option key={gedung.id} value={gedung.id}>
                                                            {gedung.name}
                                                        </option>
                                                    ))}
                                                </select>
                                            </div>
                                        </div>
                                    ) : (
                                        <div className="md:col-span-2">
                                            <label htmlFor="location_id" className="block text-sm font-medium text-gray-600">Master Lokasi</label>
                                            <div className="mt-1 flex items-center gap-2">
                                                <select name="location_id" id="location_id" value={formData.location_id} onChange={(e) => setFormData({ ...formData, location_id: e.target.value })} className="block w-full rounded-md shadow-sm border-gray-300 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                                    <option value="">-- Pilih Lokasi --</option>
                                                    {masterLocations.map(loc => (
                                                        <option key={loc.id} value={loc.id}>
                                                            {loc.name} ({loc.location_id_string})
                                                        </option>
                                                    ))}
                                                </select>
                                                <button
                                                    type="button"
                                                    onClick={() => setDeleteConfirmModal('location')}
                                                    className="p-2.5 bg-gray-100 text-gray-700 hover:bg-red-100 hover:text-red-700 rounded-md shadow-sm transition-colors"
                                                    title="Hapus penetapan lokasi dari sel ini"
                                                >
                                                    🗑️
                                                </button>
                                            </div>
                                        </div>
                                    )}
                                </div>
                            </div>
                        </div>
                        <div className="p-6 bg-gray-50 rounded-b-lg flex justify-end items-center gap-4">
                            {mapType === 'Pabrik' && (
                                <button
                                    type="button"
                                    onClick={() => setDeleteConfirmModal('gedung')}
                                    className="inline-flex items-center justify-center p-2 text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                    title="Hapus Gedung"
                                >
                                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            )}
                            <button onClick={() => setIsModalOpen(false)} className="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">Cancel</button>
                            <button onClick={saveSelection} className="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-red-700">Save Changes</button>
                        </div>
                    </div>
                </div>
            )}
            {deleteConfirmModal && (
                <div className="fixed inset-0 z-50 flex justify-center items-center p-4">
                    <div className="bg-white rounded-lg shadow-xl max-w-sm w-full">
                        <div className="p-6 text-center">
                            <svg className="mx-auto mb-4 w-14 h-14 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 className="mb-5 text-lg font-normal text-gray-500">
                                Apakah Anda yakin ingin menghapus penetapan ini?
                            </h3>
                            <p className="text-xs text-gray-400 mb-6 -mt-2">
                                Tindakan ini tidak dapat dibatalkan.
                            </p>
                            <button
                                type="button"
                                onClick={() => {
                                    if (deleteConfirmModal === 'location') {
                                        handleDeleteLocation();
                                    } else if (deleteConfirmModal === 'gedung') {
                                        handleDeleteGedungMap();
                                    }
                                    setDeleteConfirmModal(null);
                                }}
                                className="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2"
                            >
                                Ya, Hapus
                            </button>
                            <button
                                type="button"
                                onClick={() => setDeleteConfirmModal(null)}
                                className="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10"
                            >
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
};

export default GridEditor;

const element = document.getElementById('grid-editor');
if (element) {
    if (!element._reactRootContainer) { // Workaround: only create root if it doesn't exist
        createRoot(element).render(<GridEditor />);
    } else {
        // If root already exists, just render. This scenario should ideally not happen for a top-level component.
        // If it does, it indicates the script is running multiple times on the same element.
        console.warn("Attempted to call createRoot on an already mounted container. Renderer will update existing root.");
        // This line would require storing the root instance, which is not done globally here.
        // For now, checking `_reactRootContainer` prevents the error.
    }
}
