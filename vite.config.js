import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import fs from 'node:fs';
import path from 'node:path';

/**
 * Helper function to get all CSS and JS files recursively
 * Optimized with withFileTypes for better performance on Windows
 */
function getFiles(dir, files_ = []) {
    if (!fs.existsSync(dir)) return files_;
    
    try {
        const entries = fs.readdirSync(dir, { withFileTypes: true });
        
        for (const entry of entries) {
            const fullPath = path.join(dir, entry.name);
            if (entry.isDirectory()) {
                getFiles(fullPath, files_);
            } else if (entry.isFile()) {
                if (entry.name.endsWith('.css') || entry.name.endsWith('.js')) {
                    files_.push(fullPath.replace(/\\/g, '/'));
                }
            }
        }
    } catch (error) {
        console.error(`Error scanning directory ${dir}:`, error.message);
    }
    return files_;
}

const assetFiles = [
    ...getFiles('resources/css'),
    ...getFiles('resources/js')
];

export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
            input: assetFiles,
            refresh: true,
        }),
    ],
});


