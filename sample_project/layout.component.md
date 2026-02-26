## Layout Components & Styles

### 1. Authentication Layout
- **Base layout**: uses auth layout with `min-h-screen`, `bg-[#FDFDFD]`, `dark:bg-neutral-950`, `font-sans`.
- **Two-panel structure** (Login / Register / Forgot Password): left branding panel with gradient `from-[#013CFC] via-[#0031BC] to-[#60C8FC]`, right form panel on light background.
  - Login
  - Register
  - Forgot Password
- **Auth dark toggle**: `#auth-dark-toggle` button on the right panel for auth screens.

### 2. App Shell (Sidebar, Navbar, Content)
- **Main shell**: dashboard layout.
  - Body: `h-screen overflow-hidden bg-[#FDFDFD] dark:bg-neutral-950 text-gray-900 dark:text-gray-100`.
  - Structure: sidebar + navbar + scrollable main content.
  - Global notices banner at the top of content (info/success/warning/danger styles).
- **Sidebar**: left navigation panel.
  - Fixed left column: `w-72`, `bg-white dark:bg-neutral-950`, `border-r border-gray-200 dark:border-neutral-800`.
  - Active nav item: `bg-[#013CFC]/[0.08] text-[#013CFC] dark:bg-neutral-900 dark:text-[#60C8FC]`.
  - Footer card: profile preview + settings + logout.
- **Navbar**: top bar with title and actions.
  - Sticky top bar: `h-16`, `border-b border-gray-200 dark:border-neutral-800`.
  - Includes `#dark-mode-toggle` and notifications dropdown.

### 3. Tables
- **Container**: rounded card with borders and shadow: `bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 rounded-md shadow-sm overflow-hidden`.
- **Header row**: `text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider`, `bg-gray-50 dark:bg-neutral-950`.
- **Row hover**: `hover:bg-gray-50 dark:hover:bg-neutral-800/50`.
- **Status chips**: inline-flex rounded pills with semantic colors (info/success/warning/danger).

### 4. Modals
- **Standard overlay + centered dialog**:
  - Overlay: `fixed inset-0 bg-black/30 dark:bg-black/60`.
  - Centering: `fixed inset-0 flex items-center justify-center p-4 pointer-events-none`.
  - Dialog shell: `rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 shadow-xl pointer-events-auto`.
  - Header / body / footer sections follow border + padding pattern.
- **Modal actions**: primary button uses `bg-[#013CFC]` and secondary uses neutral border styles.

### 5. Message Modals & Alerts
- **Global notices (inline banners)**:
  - Uses semantic palettes for `info / success / warning / danger` with icon + close button.
- **Success alerts**:
  - `bg-emerald-50 dark:bg-emerald-900/30`, dismiss button on the right.
- **Notifications panel** (dropdown):
  - Panel shell: `rounded-md border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 shadow-xl`.

### 6. Dark Mode Functionality
- **Initialization**: theme is applied via localStorage + system preference in layout boot scripts.
- **Toggle logic**: centralized in app JS.
  - Targets `#dark-mode-toggle`, `#auth-dark-toggle`, and `.js-theme-toggle`.
  - Stores preference in `localStorage` and dispatches `theme-changed`.
- **Theme configuration**: theme config file.
  - Brand colors and dark-mode defaults.

### 7. CSS Styles & Design Tokens
- **Tailwind + Vite**: Vite builds `resources/css/app.css` + `resources/js/app.js`.
- **CSS entry**: app CSS file.
  - Brand variables: `--color-primary #013CFC`, `--color-primary-hover #0031BC`, `--color-vivid-sky #60C8FC`.
  - Global focus ring for inputs/selects: `border-color: var(--color-primary)` + `box-shadow: 0 0 0 2px rgb(1 60 252 / 35%)`.
  - Frappe Charts overrides (typography, grid, tooltip, rounded bars).
  - Sidebar nav loading state: `.sidebar-nav-link--loading` + pulse animation.
