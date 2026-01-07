# AJAX CRUD Refactor Implementation Plan

## Objective
Refactor CRUD operations for core modules to handle everything via AJAX, eliminating full page reloads for Create, Edit, Update, and Delete actions, and providing a seamless single-page application-like experience.

## Status: Started

## Modules Handled
1.  **Teachers** (Completed)
    *   Controller modified for AJAX responses (JSON/Partial View).
    *   Index view updated to use `ajax-crud.js` and DataTables.
2.  **Students** (Completed)
    *   Controller modified for AJAX responses.
    *   Index view updated to use `ajax-crud.js` and DataTables.
3.  **Schools** (Completed)
    *   Controller modified for AJAX responses.
    *   Index view updated to use `ajax-crud.js` and DataTables.
4.  **Subjects** (Completed)
    *   Controller modified for AJAX responses.
    *   Index view updated to use `ajax-crud.js` and DataTables.
5.  **Users** (Completed)
    *   Controller modified for AJAX responses.
    *   Index view updated to use `ajax-crud.js` and DataTables.
6.  **Grades** (Completed)
    *   Controller modified for AJAX responses.
    *   Index view updated to use `ajax-crud.js` and DataTables.

## Pending Modules
*   **exam-types**
*   **student-fees**
*   **teacher-salaries**
*   **roles** (Already simple, but might need update if consistent behavior is desired)

## Key Components
*   **`public/js/ajax-crud.js`**: Centralized JavaScript helper for:
    *   `openAjaxModal(url, title)`: Loading forms into a generic modal.
    *   `setupAjaxForm(form)`: Handling form submissions via AJAX (including file uploads).
    *   `deleteAjaxItem(url, itemName)`: Handling DELETE requests via AJAX with confirmation.
    *   CSRF Token handling.

## Next Steps
1.  Verify the functionality of the completed modules.
2.  Apply the same pattern to the remaining modules (`exam-types`, `student-fees`, `teacher-salaries`, etc.).
3.  Review `holidays` and `leaves` to see if they can benefit from the standardized `ajax-crud.js` or if their custom implementations should remain.
