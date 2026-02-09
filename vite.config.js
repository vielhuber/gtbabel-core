import { defineConfig } from 'vite';

export default defineConfig(({ mode }) => {
    if (mode === 'detectdomchanges') {
        return {
            build: {
                outDir: './components/detectdomchanges/build',
                rollupOptions: {
                    input: './components/detectdomchanges/script.js',
                    output: {
                        format: 'iife',
                        inlineDynamicImports: true,
                        entryFileNames: 'bundle.js'
                    }
                },
                sourcemap: false,
                minify: false,
                emptyOutDir: false
            }
        };
    }
    if (mode === 'frontendeditor') {
        return {
            build: {
                outDir: './components/frontendeditor/build',
                rollupOptions: {
                    input: './components/frontendeditor/script.js',
                    output: {
                        format: 'iife',
                        inlineDynamicImports: true,
                        entryFileNames: 'bundle.js'
                    }
                },
                sourcemap: false,
                minify: false,
                emptyOutDir: false
            }
        };
    }
});
