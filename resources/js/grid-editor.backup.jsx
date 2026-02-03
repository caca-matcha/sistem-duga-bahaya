import React from 'react';
import { createRoot } from 'react-dom/client';

const GridEditor = () => {
    return <div className="p-4 bg-yellow-100 text-yellow-800 rounded">DEBUG MODE: Grid Editor Active</div>;
};

export default GridEditor;

const element = document.getElementById('grid-editor');
if (element) {
    const root = createRoot(element);
    root.render(<GridEditor />);
}
