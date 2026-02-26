# UI Components Library

The frontend leverages a custom UI library built on top of [Reka UI](https://reka-ui.com/) (formerly Radix Vue) and styled with Tailwind CSS, heavily inspired by the **Shadcn Vue** design system. 

All core UI components are located in `resources/js/components/ui/`. These components ensure high accessibility (WAI-ARIA compliance) and visual consistency across the entire HRIS application.

---

## 1. Core Elements & Typography
These are the foundational building blocks used on almost every page.

*   **`Button`:** Centralized button component with extensive `cva()` (Class Variance Authority) variants (default, destructive, outline, secondary, ghost, link) and sizes (default, sm, lg, icon).
*   **`Alert`:** Banner notifications for critical page-level information or warnings.
*   **`Badge`:** Small status pills used extensively for distinguishing states (e.g., Pending, Approved, Rejected).
*   **`Separator`:** Accessible `hr` equivalent used to divide sections vertically or horizontally.
*   **`Skeleton`:** Animated placeholder elements used during data fetching or async operations to prevent layout shift.
*   **`Spinner`:** A custom loading indicator.

---

## 2. Forms & Inputs
Robust, accessible form elements with unified focus states and error styling.

*   **`Input`:** Standard text/password/email input fields.
*   **`Label`:** Accessible form label component tied to inputs via underlying `id`.
*   **`Checkbox`:** Custom stylized checkbox toggle.
*   **`RadioGroup`:** Grouped radio buttons for exclusive single-choice selections.
*   **`Select`:** A highly customizable Radix/Reka select dropdown with search filtering capabilities.
*   **`NativeSelect`:** A simpler, native `<select>` wrapper optimized for mobile devices or simpler form requirements.
*   **`InputOTP`:** Specialized input grouping used in the `auth` flows for Two-Factor Authentication (2FA) verification codes.

---

## 3. Data Display & Layout
Components designed to structure larger arrangements of data and primary navigation.

*   **`Card`:** The primary content wrapper consisting of nested `CardHeader`, `CardTitle`, `CardDescription`, `CardContent`, and `CardFooter`.
*   **`Breadcrumb`:** Navigation aid indicating the user's current location within the application hierarchy.
*   **`Sidebar`:** The highly complex, responsive drawer navigation system utilized globally by `AppLayout`. Includes collapsible sections, grouped menus, and state-aware triggers.
*   **`NavigationMenu`:** Horizontal grouped menu bars occasionally used in specialized layouts.

---

## 4. Overlays & Disclosures
Interactive components that sit above the rest of the UI (z-index managed).

*   **`Dialog` (Modal):** A centered overlay window used extensively for destructive confirmations, creating records (e.g., Let's Add User), or editing complex entities.
*   **`Sheet`:** A slide-out panel (usually from the right edge) providing additional space without leaving the context of the current page.
*   **`Popover`:** A contextual floating panel attached to a trigger element (commonly used for date pickers).
*   **`Tooltip`:** Brief, informative popups that appear on hover or focus.
*   **`DropdownMenu`:** Contextual action menus often triggered by an ellipsis (three dots) icon, heavily utilized in the "Actions" columns of data tables.
*   **`Collapsible`:** Accordion-style expandable/collapsible details panels.

---

## 5. Specialized Implementations
*   **`Calendar` & `DatePickers`:** Custom date selection interfaces, heavily integrated with the Leave Application and Holiday Management modules.
*   **`Avatar`:** Dynamic user profile image renderer. Features automatic fallback to initials (`AvatarFallback`) if the `AvatarImage` fails to load or no avatar is set.
