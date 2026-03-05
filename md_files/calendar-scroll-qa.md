# Calendar Scroll QA Checklist

## Scope
- Calendar card scrolling in Admin, HR, and Employee calendar pages
- Views: Month, Week, Day, List, Year

## Desktop Browsers
- Chrome
  - Month view: horizontal scroll not needed, vertical scroll not trapped
  - Week/Day view: vertical scroll works inside card
  - List view: vertical scroll works inside card
  - Date click and event click still trigger
- Firefox
  - Repeat Chrome checks
- Edge
  - Repeat Chrome checks
- Safari (macOS)
  - Two-finger scroll works inside card
  - No scroll lock when cursor is over calendar grid

## Mobile Browsers
- iOS Safari
  - Touch scroll works inside calendar card
  - Horizontal scroll works for week grid when needed
  - Date tap and swipe navigation still work
- Android Chrome
  - Repeat iOS checks

## Regression Targets
- Sidebar toggle resize does not break scroll
- Modals (event detail, date events) still scroll independently
