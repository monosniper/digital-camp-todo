import { defineConfig } from 'vite';

export default defineConfig({
	root: 'assets',
	build: {
		outDir: '../public/dist',
		emptyOutDir: true,
		rollupOptions: {
			input: 'assets/js/app.js',
			output: {
				entryFileNames: `[name].js`,
				chunkFileNames: `[name].js`,
				assetFileNames: `[name].[ext]`,
			},
		},
	},
});
