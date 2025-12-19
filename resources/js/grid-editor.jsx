import React, { useState, useEffect, useRef } from 'react';
import { createRoot } from 'react-dom/client';
import { Stage, Layer, Rect, Text, Image } from 'react-konva';
import axios from 'axios';

const GridEditor = () => {
    const { id: mapId, rows, cols, background_image } = window.mapData;
    
    const containerRef = useRef(null);
    const [containerWidth, setContainerWidth] = useState(0);

    const [cells, setCells] = useState([]);
    const [selectedCells, setSelectedCells] = useState([]);
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectionRect, setSelectionRect] = useState({ x: 0, y: 0, width: 0, height: 0, visible: false });
    const selectionStarted = useRef(false);
    const isSelecting = useRef(false);

    const [formData, setFormData] = useState({
        area_id: '',
        area_name: '',
        area_type: '',
        metadata: {},
        risk_parameters: [],
    });
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
            img.onload = () => {
                setBackgroundImage(img);
            };
        }
    }, [background_image]);

    useEffect(() => {
        if (!isModalOpen) setError(null);
    }, [isModalOpen]);

    useEffect(() => {
        axios.get(`/she/api/maps/${mapId}/cells`)
            .then(response => {
                setCells(response.data);
                setError(null);
            })
            .catch(err => {
                console.error("Error fetching cells:", err);
                setError("Failed to load map cells. " + (err.response?.data?.message || err.message));
            });
    }, [mapId]);

    const getCellData = (rowIndex, colIndex) => {
        return cells.find(cell => cell.row_index === rowIndex && cell.col_index === colIndex);
    };

    const handleCellClick = (rowIndex, colIndex) => {
        setError(null);
        const cell = { row_index: rowIndex, col_index: colIndex };
        const existingCell = getCellData(rowIndex, colIndex);
        
        setSelectedCells([cell]); // Select only one cell

        setFormData({
            area_id: existingCell?.area_id || '',
            area_name: existingCell?.area_name || '',
            area_type: existingCell?.area_type || '',
        });
        setIsModalOpen(true);
    };

    const handleFormChange = (e) => {
        const { name, value } = e.target;
        setFormData(prev => ({ ...prev, [name]: value }));
    };

    // Note: Risk parameter editing is disabled for multi-cell selection for simplicity.
    // This section in the modal will be hidden when more than one cell is selected.
    const handleRiskParamChange = (index, field, value) => {
        // This function remains for single-cell editing if ever re-enabled.
    };
    const addRiskParameter = () => {};
    const removeRiskParameter = (index) => {};

    const saveSelection = () => {
        if (selectedCells.length === 0) return;
        setError(null);

        const payload = {
            map_id: mapId,
            cells: selectedCells, // Array of {row_index, col_index}
            area_id: formData.area_id,
            area_name: formData.area_name,
            area_type: formData.area_type,
        };

        axios.post(`/she/api/cells/batch-update`, payload)
            .then(response => {
                // Refetch all cells to get the updated data
                 axios.get(`/she/api/maps/${mapId}/cells`)
                    .then(response => {
                        setCells(response.data);
                    });
                
                setIsModalOpen(false);
                setSelectedCells([]);
            })
            .catch(err => {
                console.error("Error batch saving cells:", err);
                const errorMessage = err.response?.data?.message || err.message;
                const errors = err.response?.data?.errors;
                let fullErrorMessage = errorMessage;
                if (errors) {
                    fullErrorMessage += "\n" + Object.values(errors).flat().join("\n");
                }
                setError("Failed to save selection: " + fullErrorMessage);
            });
    };

    const handleMouseDown = (e) => {
        // Ignore if not left-click or if clicking on something other than the stage background
        if (e.target !== e.target.getStage()) {
            return;
        }
        e.evt.preventDefault();
        selectionStarted.current = true;
        isSelecting.current = false;
        const pos = e.target.getStage().getPointerPosition();
        setSelectionRect({ x: pos.x, y: pos.y, width: 0, height: 0, visible: true });
        setSelectedCells([]); // Clear previous selection
    };

    const handleMouseMove = (e) => {
        if (!selectionStarted.current) {
            return;
        }
        e.evt.preventDefault();
        isSelecting.current = true;
        const pos = e.target.getStage().getPointerPosition();
        setSelectionRect(prevRect => ({
            ...prevRect,
            width: pos.x - prevRect.x,
            height: pos.y - prevRect.y,
        }));
    };

    const handleMouseUp = (e) => {
        selectionStarted.current = false;
        setSelectionRect(prev => ({...prev, visible: false}));

        // If it was just a click (not a drag), handle as single cell click
        if (!isSelecting.current) {
            const pos = e.target.getStage().getPointerPosition();
            const row = Math.floor(pos.y / (stageHeight / rows));
            const col = Math.floor(pos.x / (stageWidth / cols));
            if(row < rows && col < cols) {
                handleCellClick(row, col);
            }
            return;
        }
        isSelecting.current = false;
        
        const newSelectedCells = [];
        const { x, y, width, height } = selectionRect;
        const sx = x < x + width ? x : x + width;
        const sy = y < y + height ? y : y + height;
        const ex = x > x + width ? x : x + width;
        const ey = y > y + height ? y : y + height;

        for (let i = 0; i < rows; i++) {
            for (let j = 0; j < cols; j++) {
                const cellX = j * cellWidth;
                const cellY = i * cellHeight;
                if (cellX < ex && cellX + cellWidth > sx && cellY < ey && cellY + cellHeight > sy) {
                    newSelectedCells.push({ row_index: i, col_index: j });
                }
            }
        }

        if (newSelectedCells.length > 0) {
            setSelectedCells(newSelectedCells);
            const firstCell = getCellData(newSelectedCells[0].row_index, newSelectedCells[0].col_index);
            setFormData({
                area_id: firstCell?.area_id || '',
                area_name: firstCell?.area_name || '',
                area_type: firstCell?.area_type || '',
            });
            setIsModalOpen(true);
        }
    };


    // Calculate stage and cell dimensions based on container width and image aspect ratio
    let stageWidth = containerWidth;
    let stageHeight = 0;
    if (backgroundImage && containerWidth > 0) {
        const aspectRatio = backgroundImage.naturalWidth / backgroundImage.naturalHeight;
        stageHeight = containerWidth / aspectRatio;
    }

    const cellWidth = stageWidth / cols;
    const cellHeight = stageHeight / rows;

    const gridElements = [];
    for (let i = 0; i < rows; i++) {
        for (let j = 0; j < cols; j++) {
            const cellData = getCellData(i, j);
            const isSelected = selectedCells.some(c => c.row_index === i && c.col_index === j);
            const fillColor = cellData?.zone_color || 'white';
            
            gridElements.push(
                <Rect
                    key={`rect-${i}-${j}`}
                    x={j * cellWidth}
                    y={i * cellHeight}
                    width={cellWidth}
                    height={cellHeight}
                    fill={fillColor}
                    stroke={isSelected ? 'blue' : 'black'}
                    strokeWidth={isSelected ? 2 : 0.5}
                    opacity={isSelected ? 0.9 : 0.7}
                    onClick={() => handleCellClick(i, j)}
                    onTap={() => handleCellClick(i, j)}
                />,
                <Text
                    key={`text-${i}-${j}`}
                    x={j * cellWidth + 5}
                    y={i * cellHeight + 5}
                    text={(cellData?.risk_score ?? '').toString()}
                    fontSize={14}
                    fill="black"
                    listening={false}
                />
            );
        }
    }

    return (
        <div ref={containerRef}>
            {/* Main error display */}
            {error && !isModalOpen && (
                <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong className="font-bold">Error!</strong>
                    <span className="block sm:inline ml-2">{error}</span>
                    <span className="absolute top-0 bottom-0 right-0 px-4 py-3" onClick={() => setError(null)}>
                        <svg className="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 010 1.698z"/></svg>
                    </span>
                </div>
            )}
            {stageHeight > 0 && (
                 <Stage
                    width={stageWidth}
                    height={stageHeight}
                    onMouseDown={handleMouseDown}
                    onMouseMove={handleMouseMove}
                    onMouseUp={handleMouseUp}
                    className="bg-gray-50 border border-gray-300 rounded-lg shadow-inner"
                >
                    <Layer>
                        {backgroundImage && (
                            <Image
                                image={backgroundImage}
                                width={stageWidth}
                                height={stageHeight}
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
                </Stage>
            )}

            {/* Color Zone Legend */}
            <div className="mt-4 p-4 bg-white rounded-lg shadow">
                <h4 className="font-bold text-gray-800 mb-2">Risk Zone Legend</h4>
                <div className="flex items-center mb-1">
                    <span className="block w-6 h-4 bg-green-500 rounded-sm mr-2 border border-gray-300"></span>
                    <span className="text-sm text-gray-700">0-3 (Low Risk)</span>
                </div>
                <div className="flex items-center mb-1">
                    <span className="block w-6 h-4 bg-yellow-500 rounded-sm mr-2 border border-gray-300"></span>
                    <span className="text-sm text-gray-700">4-7 (Medium Risk)</span>
                </div>
                <div className="flex items-center">
                    <span className="block w-6 h-4 bg-red-600 rounded-sm mr-2 border border-gray-300"></span>
                    <span className="text-sm text-gray-700">8-10 (High Risk)</span>
                </div>
            </div>

            {/* Color Zone Legend */}
            <div className="mt-4 p-4 bg-white rounded-lg shadow">
                <h4 className="font-bold text-gray-800 mb-2">Risk Zone Legend</h4>
                <div className="flex items-center mb-1">
                    <span className="block w-6 h-4 bg-green-500 rounded-sm mr-2 border border-gray-300"></span>
                    <span className="text-sm text-gray-700">0-3 (Low Risk)</span>
                </div>
                <div className="flex items-center mb-1">
                    <span className="block w-6 h-4 bg-yellow-500 rounded-sm mr-2 border border-gray-300"></span>
                    <span className="text-sm text-gray-700">4-7 (Medium Risk)</span>
                </div>
                <div className="flex items-center">
                    <span className="block w-6 h-4 bg-red-600 rounded-sm mr-2 border border-gray-300"></span>
                    <span className="text-sm text-gray-700">8-10 (High Risk)</span>
                </div>
            </div>

            {isModalOpen && (
                <div className="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center p-4">
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

                            {/* Area Information Section */}
                            <div className="mb-6">
                                <h4 className="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Area Information</h4>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label htmlFor="area_id" className="block text-sm font-medium text-gray-600">Area ID</label>
                                        <input type="text" name="area_id" id="area_id" value={formData.area_id} onChange={handleFormChange} className="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50" />
                                    </div>
                                    <div>
                                        <label htmlFor="area_name" className="block text-sm font-medium text-gray-600">Area Name</label>
                                        <input type="text" name="area_name" id="area_name" value={formData.area_name} onChange={handleFormChange} className="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50" />
                                    </div>
                                    <div className="md:col-span-2">
                                        <label htmlFor="area_type" className="block text-sm font-medium text-gray-600">Area Type</label>
                                        <input type="text" name="area_type" id="area_type" value={formData.area_type} onChange={handleFormChange} className="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Modal Footer */}
                        <div className="p-6 bg-gray-50 rounded-b-lg flex justify-end items-center gap-4">
                            <button onClick={() => setIsModalOpen(false)} className="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">Cancel</button>
                            <button onClick={saveSelection} className="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-red-700">Save Changes</button>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
};

const container = document.getElementById('grid-editor');
if (container) {
    const root = createRoot(container);
    root.render(<GridEditor />);
}