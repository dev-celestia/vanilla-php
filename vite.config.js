import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';
import liveReload from 'vite-plugin-live-reload';

export default defineConfig({
  plugins: [
    tailwindcss(),
    // Auto-reload browser when any PHP template or component is edited
    liveReload([
      './*.php',
      './ui/**/*.php',
      './components/**/*.php',
      './admin/**/*.php',
      './includes/**/*.php',
      './helpers/**/*.php',
      './config/**/*.php',
    ]),
    // Print clear accessible app URLs in terminal
    {
      name: 'app-banner',
      configureServer(server) {
        server.httpServer?.once('listening', () => {
          setTimeout(() => {
            console.log('\n  \x1b[32m\x1b[1m🚀 App Running (PHP Backend):\x1b[0m');
            console.log('  \x1b[36m➜  Local:\x1b[0m   http://localhost:8000');
            console.log('  \x1b[36m➜  Admin:\x1b[0m   http://localhost:8000/admin/login.php');
            console.log('  \x1b[90m(Vite is running on port 5173 for CSS/JS HMR)\x1b[0m\n');
          }, 150);
        });
      },
    },
  ],
  server: {
    host: true,
    port: 5173,
    strictPort: true,
    cors: true,
  },
  build: {
    // Generate .vite/manifest.json for PHP backend integration
    manifest: true,
    outDir: 'dist',
    rollupOptions: {
      input: {
        main: './resources/js/main.js',
      },
    },
  },
});
