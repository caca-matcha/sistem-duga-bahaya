import React, { useState, useEffect, useRef } from 'react';
import { createRoot } from 'react-dom/client';
import { Stage, Layer, Rect, Text, Image } from 'react-konva';
import axios from 'axios';

const GridEditor = () => {
    const { id: mapId, rows, cols, background_image } = window.mapData;
    const cellWidth = 50;
    const cellHeight = 50;
    const stageWidth = cols * cellWidth;
    const stageHeight = rows * cellHeight;

    const [cells, setCells] = useState([]);
    const [selectedCell, setSelectedCell] = useState(null);
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [formData, setFormData] = useState({
        area_id: '',
        area_name: '',
        area_type: '',
        metadata: {},
        risk_parameters: [],
    });
    const [error, setError] = useState(null);
    const [backgroundImage, setBackgroundImage] = useState(null);

    // State for zoom and pan
    const [stageScale, setStageScale] = useState(1);
    const [stageX, setStageX] = useState(0);
    const [stageY, setStageY] = useState(0);
    const isDragging = useRef(false);
    const lastPointerPosition = useRef(null);


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
        const existingCell = getCellData(rowIndex, colIndex);
        setSelectedCell({
            map_id: mapId,
            row_index: rowIndex,
            col_index: colIndex,
            ...(existingCell || {}),
        });
        setFormData({
            area_id: existingCell?.area_id || '',
            area_name: existingCell?.area_name || '',
            area_type: existingCell?.area_type || '',
            metadata: existingCell?.metadata || {},
            risk_parameters: existingCell?.risk_parameters || [],
        });
        setIsModalOpen(true);
    };

    const handleFormChange = (e) => {
        const { name, value } = e.target;
        setFormData(prev => ({ ...prev, [name]: value }));
    };

    const handleRiskParamChange = (index, field, value) => {
        const newParams = [...formData.risk_parameters];
        newParams[index][field] = value;
        setFormData(prev => ({ ...prev, risk_parameters: newParams }));
    };

    const addRiskParameter = () => {
        setFormData(prev => ({
            ...prev,
            risk_parameters: [...prev.risk_parameters, { parameter_name: '', value: 0 }]
        }));
    };

    const removeRiskParameter = (index) => {
        const newParams = formData.risk_parameters.filter((_, i) => i !== index);
        setFormData(prev => ({ ...prev, risk_parameters: newParams }));
    };

    const saveCell = () => {
        setError(null);
        const payload = {
            map_id: selectedCell.map_id,
            row_index: selectedCell.row_index,
            col_index: selectedCell.col_index,
            area_id: formData.area_id,
            area_name: formData.area_name,
            area_type: formData.area_type,
            metadata: formData.metadata,
            risk_parameters: formData.risk_parameters.filter(p => p.parameter_name), // Only send params with a name
        };

        axios.post('/she/api/cells', payload)
            .then(response => {
                const updatedCell = response.data;
                setCells(prevCells => {
                    const existingIndex = prevCells.findIndex(c => 
                        c.row_index === updatedCell.row_index && c.col_index === updatedCell.col_index
                    );
                    if (existingIndex > -1) {
                        const newCells = [...prevCells];
                        newCells[existingIndex] = updatedCell;
                        return newCells;
                    }
                    return [...prevCells, updatedCell];
                });
                setIsModalOpen(false);
                setSelectedCell(null);
            })
            .catch(err => {
                console.error("Error saving cell:", err);
                const errorMessage = err.response?.data?.message || err.message;
                const errors = err.response?.data?.errors;
                let fullErrorMessage = errorMessage;
                if (errors) {
                    fullErrorMessage += "\n" + Object.values(errors).flat().join("\n");
                }
                setError("Failed to save cell: " + fullErrorMessage);
            });
    };

    const handleWheel = (e) => {
        e.evt.preventDefault();
        const stage = e.target.getStage();
        const oldScale = stage.scaleX();
        const pointer = stage.getPointerPosition();

        const mousePointTo = {
            x: (pointer.x - stage.x()) / oldScale,
            y: (pointer.y - stage.y()) / oldScale,
        };

        const newScale = e.evt.deltaY > 0 ? oldScale * 1.1 : oldScale / 1.1; // Zoom out/in

        setStageScale(newScale);
        setStageX(pointer.x - mousePointTo.x * newScale);
        setStageY(pointer.y - mousePointTo.y * newScale);
    };

    const handleMouseDown = (e) => {
        if (e.evt.button === 0) { // Left mouse button
            isDragging.current = true;
            lastPointerPosition.current = e.target.getStage().getPointerPosition();
        }
    };

    const handleMouseMove = (e) => {
        if (isDragging.current) {
            const stage = e.target.getStage();
            const newPointerPosition = stage.getPointerPosition();
            if (lastPointerPosition.current) {
                setStageX(stageX + (newPointerPosition.x - lastPointerPosition.current.x));
                setStageY(stageY + (newPointerPosition.y - lastPointerPosition.current.y));
                lastPointerPosition.current = newPointerPosition;
            }
        }
    };

    const handleMouseUp = () => {
        isDragging.current = false;
        lastPointerPosition.current = null;
    };


    const gridElements = [];
    for (let i = 0; i < rows; i++) {
        for (let j = 0; j < cols; j++) {
            const cellData = getCellData(i, j);
            const fillColor = cellData?.zone_color || 'white';
            gridElements.push(
                <Rect
                    key={`rect-${i}-${j}`}
                    x={j * cellWidth}
                    y={i * cellHeight}
                    width={cellWidth}
                    height={cellHeight}
                    fill={fillColor}
                    stroke="black"
                    strokeWidth={1}
                    opacity={0.7} // Make grid semi-transparent to see background
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
        <div>
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
            <Stage
                width={stageWidth}
                height={stageHeight}
                scaleX={stageScale}
                scaleY={stageScale}
                x={stageX}
                y={stageY}
                onWheel={handleWheel}
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
                <Layer>{gridElements}</Layer>
            </Stage>

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
                            <h3 className="text-2xl font-bold text-gray-800">Edit Cell ({selectedCell?.row_index}, {selectedCell?.col_index})</h3>
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

                            {/* Risk Assessment Section */}
                            <div className="mb-6">
                                <h4 className="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Risk Assessment</h4>
                                <div className="space-y-3">
                                    {formData.risk_parameters.map((param, index) => (
                                        <div key={index} className="flex items-center gap-2">
                                            <input type="text" placeholder="Parameter Name" value={param.parameter_name} onChange={(e) => handleRiskParamChange(index, 'parameter_name', e.target.value)} className="flex-1 rounded-md shadow-sm border-gray-300 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50"/>
                                            <input type="number" placeholder="Value" value={param.value} onChange={(e) => handleRiskParamChange(index, 'value', e.target.value)} className="w-24 rounded-md shadow-sm border-gray-300 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50"/>
                                            <button onClick={() => removeRiskParameter(index)} className="p-2 text-gray-500 hover:text-red-600">
                                                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                    ))}
                                </div>
                                <button onClick={addRiskParameter} className="mt-3 inline-flex items-center px-3 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                                    Add Parameter
                                </button>
                            </div>
                            
                            {/* Calculated Scores */}
                            <div className="mb-6 p-4 bg-gray-50 rounded-lg border">
                                <h4 className="text-lg font-semibold text-gray-700 mb-2">Calculated Result</h4>
                                <div className="grid grid-cols-2 gap-4">
                                    <div>
                                        <p className="text-sm text-gray-500">Risk Score</p>
                                        <p className="text-2xl font-bold text-gray-900">{selectedCell?.risk_score ?? 'N/A'}</p>
                                    </div>
                                    <div>
                                        <p className="text-sm text-gray-500">Zone Color</p>
                                        <div className="flex items-center">
                                            <span className="block w-6 h-6 rounded-md mr-2 border" style={{ backgroundColor: selectedCell?.zone_color || 'white' }}></span>
                                            <p className="text-lg font-semibold capitalize">{selectedCell?.zone_color ?? 'N/A'}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Modal Footer */}
                        <div className="p-6 bg-gray-50 rounded-b-lg flex justify-end items-center gap-4">
                            <button onClick={() => setIsModalOpen(false)} className="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">Cancel</button>
                            {selectedCell?.id && (
                                <button onClick={deleteCell} className="px-4 py-2 text-sm font-medium text-white bg-gray-600 border border-transparent rounded-md shadow-sm hover:bg-gray-700">Delete Cell Data</button>
                            )}
                            <button onClick={saveCell} className="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-red-700">Save Changes</button>
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