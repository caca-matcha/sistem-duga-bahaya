import React, { useState, useEffect, useCallback, useMemo } from 'react';
import { Stage, Layer, Image, Rect } from 'react-konva';
import { createRoot } from 'react-dom/client';

// NOTE: Saya harus mengasumsikan definisi MapViewer dan hooks/state yang digunakan
// (stageWidth, stageHeight, stageScale, stageX, stageY, handleWheel, handleMouseDown,
// handleMouseMove, handleMouseUp, gridElements, minimapScale, cells, cellWidth, cellHeight,
// isModalOpen, selectedCell, setIsModalOpen, searchTerm, setSearchTerm, error, setError, 
// backgroundImage, dll.) ada di bagian atas file Anda.

// --- AWAL KOMPONEN REACT (Diasumsikan ini adalah komponen MapViewer) ---
const MapViewer = () => {
    // Definisi state dan hooks (harus ada di sini)
    const [stageWidth, setStageWidth] = useState(1000);
    const [stageHeight, setStageHeight] = useState(600);
    const [stageScale, setStageScale] = useState(1);
    const [stageX, setStageX] = useState(0);
    const [stageY, setStageY] = useState(0);
    const [backgroundImage, setBackgroundImage] = useState(null);
    const [error, setError] = useState(null);
    const [searchTerm, setSearchTerm] = useState('');
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedCell, setSelectedCell] = useState(null);
    const minimapScale = 0.2; // Contoh nilai
    const cells = []; // Contoh data sel
    const cellWidth = 50; // Contoh nilai
    const cellHeight = 50; // Contoh nilai
    const gridElements = <Layer></Layer>; // Contoh elemen grid

    // Fungsi handler (harus ada di sini)
    const handleWheel = () => {};
    const handleMouseDown = () => {};
    const handleMouseMove = () => {};
    const handleMouseUp = () => {};

    // --- AWAL KODE YANG ANDA BERIKAN (BAGIAN RETURN) ---
    return (
        <div className="flex"> {/* Added flex container */}
            <div className="flex-1 mr-4"> {/* Main Map Container */}
                {/* Main error display */}
                {error && (
                    <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong className="font-bold">Error!</strong>
                        <span className="block sm:inline ml-2">{error}</span>
                        <span className="absolute top-0 bottom-0 right-0 px-4 py-3" onClick={() => setError(null)}>
                            <svg className="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 010 1.698z"/></svg>
                        </span>
                    </div>
                )}

                <div className="mb-4">
                    <input
                        type="text"
                        placeholder="Search by Area ID or Name"
                        value={searchTerm}
                        onChange={(e) => setSearchTerm(e.target.value)}
                        className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50"
                    />
                </div>

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
            </div>

            {/* Mini-map and other side content */}
            <div className="w-64"> {/* Define a fixed width for the sidebar content */}
                <div className="p-4 bg-white rounded-lg shadow mb-4">
                    <h4 className="font-bold text-gray-800 mb-2">Mini Map</h4>
                    <Stage
                        width={stageWidth * minimapScale}
                        height={stageHeight * minimapScale}
                        className="bg-gray-50 border border-gray-300 rounded-lg shadow-inner"
                    >
                        <Layer scaleX={minimapScale} scaleY={minimapScale}>
                            {backgroundImage && (
                                <Image
                                    image={backgroundImage}
                                    width={stageWidth}
                                    height={stageHeight}
                                />
                            )}
                            {/* Render a simplified grid for minimap */}
                            {cells.map(cell => (
                                <Rect
                                    key={`minimap-rect-${cell.row_index}-${cell.col_index}`}
                                    x={cell.col_index * cellWidth}
                                    y={cell.row_index * cellHeight}
                                    width={cellWidth}
                                    height={cellHeight}
                                    fill={cell.zone_color || 'white'}
                                    stroke="black"
                                    strokeWidth={0.5}
                                    opacity={0.7}
                                />
                            ))}
                            {/* Visible area rectangle */}
                            <Rect
                                x={-stageX / stageScale}
                                y={-stageY / stageScale}
                                width={stageWidth / stageScale}
                                height={stageHeight / stageScale}
                                stroke="blue"
                                strokeWidth={5 / minimapScale} // Adjust stroke width for minimap scale
                                dash={[10 / minimapScale, 5 / minimapScale]}
                            />
                        </Layer>
                    </Stage>
                </div>
            </div>

            {isModalOpen && selectedCell && (
                <div className="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center p-4">
                    <div className="bg-white p-6 rounded-lg shadow-xl max-w-sm w-full">
                        <div className="p-6 border-b">
                            <h3 className="text-2xl font-bold text-gray-800">Cell Details ({selectedCell.row_index},{selectedCell.col_index})</h3>
                        </div>
                        <div className="p-6">
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p className="block text-sm font-medium text-gray-600"><strong>Area ID:</strong></p>
                                    <p className="mt-1 text-gray-900">{selectedCell.area_id || 'N/A'}</p>
                                </div>
                                <div>
                                    <p className="block text-sm font-medium text-gray-600"><strong>Area Name:</strong></p>
                                    <p className="mt-1 text-gray-900">{selectedCell.area_name || 'N/A'}</p>
                                </div>
                                <div>
                                    <p className="block text-sm font-medium text-gray-600"><strong>Area Type:</strong></p>
                                    <p className="mt-1 text-gray-900">{selectedCell.area_type || 'N/A'}</p>
                                </div>
                            </div>
                            
                            <div className="mt-6">
                                <h4 className="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Risk Assessment</h4>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p className="block text-sm font-medium text-gray-600"><strong>Risk Score:</strong></p>
                                        <p className="mt-1 text-2xl font-bold text-gray-900">{selectedCell.risk_score || 'N/A'}</p>
                                    </div>
                                    <div>
                                        <p className="block text-sm font-medium text-gray-600"><strong>Zone Color:</strong></p>
                                        <div className="flex items-center mt-1">
                                            <span className="block w-6 h-6 rounded-md mr-2 border" style={{ backgroundColor: selectedCell.zone_color || 'white' }}></span>
                                            <p className="text-lg font-semibold capitalize">{selectedCell.zone_color || 'N/A'}</p>
                                        </div>
                                    </div>
                                </div>
                                {selectedCell.risk_parameters && selectedCell.risk_parameters.length > 0 && (
                                    <div className="mt-4">
                                        <p className="block text-sm font-medium text-gray-600"><strong>Parameters:</strong></p>
                                        <ul className="list-disc list-inside ml-4 mt-2 text-gray-800">
                                            {selectedCell.risk_parameters.map((param, index) => (
                                                <li key={index}>{param.parameter_name}: {param.value}</li>
                                            ))}
                                        </ul>
                                    </div>
                                )}
                            </div>

                            {selectedCell.metadata && Object.keys(selectedCell.metadata).length > 0 && (
                                <div className="mt-6">
                                    <h4 className="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Metadata</h4>
                                    <ul className="list-disc list-inside ml-4 text-gray-800">
                                        {Object.entries(selectedCell.metadata).map(([key, value]) => (
                                            <li key={key}>{key}: {value}</li>
                                        ))}
                                    </ul>
                                </div>
                            )}
                        </div>

                        {/* Modal Footer */}
                        <div className="p-6 bg-gray-50 rounded-b-lg flex justify-end items-center gap-4 border-t">
                            <button onClick={() => setIsModalOpen(false)} className="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">Close</button>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}; // <-- KURUNG KURAWAL PENUTUP KOMPONEN YANG BENAR

const container = document.getElementById('map-viewer');
if (container) {
    const root = createRoot(container);
    root.render(<MapViewer />);
}
// --- AKHIR KODE ---