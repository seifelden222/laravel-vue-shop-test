# Frontend Documentation (Vue.js + Vite)

This directory contains the frontend source code for the E-Commerce Dashboard, built with **Vue.js 3**, **TypeScript**, and **Tailwind CSS**, bundled via **Vite**.

## Prerequisites

Ensure you have the following installed on your machine:

- **Node.js**: >= 16.x
- **NPM**: (comes with Node.js)

## Installation

1.  **Navigate to the project root directory.**

2.  **Install JavaScript dependencies:**
    ```bash
    npm install
    ```

## Running Development Server

To start the hot-reloading development server:

```bash
npm run dev
```

This will start the Vite server. You must also have the Laravel backend running (`php artisan serve`) for the application to function correctly, as it serves the entry point blade template.

Access the application at: `http://localhost:8000`

## Building for Production

To compile and minify the assets for production deployment:

```bash
npm run build
```

This command generates the optimized assets in the `public/build` directory.

## Project Structure

The frontend code is located in `resources/js`:

- **`components/`**: Reusable UI components (e.g., `Modal.vue`, `StatCard.vue`).
- **`layouts/`**: Layout components (e.g., `Layout.vue` for the sidebar).
- **`pages/`**: Application views/pages.
    - `auth/`: Login and Register pages.
    - `Dashboard.vue`: Main dashboard with statistics.
    - `Products.vue`: Product management (CRUD).
    - `Orders.vue`: Order listing and details.
- **`router/`**: Vue Router configuration (`index.ts`).
- **`app.ts`**: Main entry point.
