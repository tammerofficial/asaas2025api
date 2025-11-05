<style>
/* ============================================
   Elementor-Style PageBuilder Interface
   Clean, Modern, Professional
   ============================================ */

/* Main Wrapper - Isolated from dashboard styles */
.pagebuilder-elementor-wrapper {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    flex-direction: column;
    background: #f1f3f5;
    z-index: 9999;
    overflow: hidden;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    /* Reset & Base - Only for pagebuilder wrapper */
    box-sizing: border-box;
}

/* Scoped reset - Only affects elements inside pagebuilder wrapper */
.pagebuilder-elementor-wrapper *,
.pagebuilder-elementor-wrapper *::before,
.pagebuilder-elementor-wrapper *::after {
    box-sizing: border-box;
}

/* ============================================
   TOP BAR - Elementor Dark Style
   ============================================ */
.pb-top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0;
    background: #1c1c1c;
    border-bottom: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    z-index: 100;
    height: 60px;
    flex-shrink: 0;
}

.pb-top-left,
.pb-top-right {
    display: flex;
    align-items: center;
    height: 100%;
}

.pb-top-left {
    gap: 0;
}

.pb-top-right {
    gap: 8px;
    padding-right: 16px;
}

/* Page Title */
.pb-page-title {
    padding: 0 20px;
    font-size: 14px;
    font-weight: 500;
    color: #a4afb7;
    border-left: 1px solid rgba(255,255,255,0.08);
    height: 100%;
    display: flex;
    align-items: center;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 300px;
}

/* Buttons - Clean Elementor Style */
.pb-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 0 16px;
    border: none;
    background: transparent;
    cursor: pointer;
    font-size: 13px;
    font-weight: 500;
    transition: all 0.2s ease;
    color: #a4afb7;
    height: 60px;
    border-right: 1px solid rgba(255,255,255,0.05);
}

.pb-btn i {
    font-size: 18px;
}

.pb-btn:hover {
    background: rgba(255,255,255,0.05);
    color: #fff;
}

.pb-btn-toggle-sidebar {
    width: 60px;
    padding: 0;
}

.pb-btn-save {
    background: #7f1625;
    color: #fff;
    border-right: none;
    margin-left: 8px;
    border-radius: 3px;
    height: 38px;
    padding: 0 20px;
}

.pb-btn-save:hover {
    background: #9a1b2e;
}

.pb-status {
    font-size: 12px;
    font-weight: 500;
    color: #a4afb7;
    padding: 8px 14px;
    background: rgba(255,255,255,0.05);
    border-radius: 3px;
}

/* ============================================
   MAIN CONTENT - 3 Column Layout
   ============================================ */
.pb-main-content {
    display: flex;
    flex: 1;
    overflow: hidden;
    position: relative;
    gap: 0;
    background: #f1f3f5;
}

/* ============================================
   SIDEBAR - Left Panel (Widgets)
   ============================================ */
.pb-sidebar {
    width: 300px;
    background: #fff;
    border-right: 1px solid #e6e9ec;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    transition: transform 0.3s ease, width 0.3s ease;
    flex-shrink: 0;
}

.pb-sidebar.minimized {
    transform: translateX(-100%);
    width: 0;
}

.pb-sidebar-content {
    display: flex;
    flex-direction: column;
    height: 100%;
    overflow: hidden;
}

/* Search */
.pb-sidebar-search {
    padding: 15px;
    border-bottom: 1px solid #e6e9ec;
}

.pb-sidebar-search input {
    width: 100%;
    padding: 10px 12px 10px 38px;
    border: 1px solid #d4d7dc;
    border-radius: 3px;
    font-size: 13px;
    transition: border-color 0.2s;
    background: #f8f9fa;
}

.pb-sidebar-search input:focus {
    outline: none;
    border-color: #7f1625;
    background: #fff;
}

.pb-sidebar-search .form-group {
    position: relative;
    margin: 0;
}

.pb-sidebar-search .mdi-magnify {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #93959f;
    font-size: 18px;
}

/* Tabs */
.pb-sidebar-tabs {
    border-bottom: 1px solid #e6e9ec;
}

.pb-sidebar-tabs .nav-tabs {
    border: none;
    display: flex;
    margin: 0;
}

.pb-sidebar-tabs .nav-link {
    flex: 1;
    border: none;
    border-radius: 0;
    padding: 12px 10px;
    color: #6d7882;
    font-size: 13px;
    font-weight: 500;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: all 0.2s;
    background: transparent;
    border-bottom: 2px solid transparent;
}

.pb-sidebar-tabs .nav-link:hover {
    color: #495157;
    background: #f8f9fa;
}

.pb-sidebar-tabs .nav-link.active {
    color: #7f1625;
    background: transparent;
    border-bottom-color: #7f1625;
}

.pb-sidebar-tabs .nav-link i {
    font-size: 16px;
}

/* Widgets List */
.pb-sidebar-widgets {
    flex: 1;
    overflow-y: auto;
    padding: 10px;
}

.pb-sidebar-widgets::-webkit-scrollbar {
    width: 6px;
}

.pb-sidebar-widgets::-webkit-scrollbar-track {
    background: transparent;
}

.pb-sidebar-widgets::-webkit-scrollbar-thumb {
    background: #d4d7dc;
    border-radius: 10px;
}

.pb-widget-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 8px;
}

/* Widget Category Headers */
.pb-widget-category {
    grid-column: 1 / -1;
    padding: 12px 0 8px;
    margin-top: 12px;
    border-bottom: 1px solid #e6e9ec;
}

.pb-widget-category:first-child {
    margin-top: 0;
}

.pb-widget-category h5 {
    margin: 0;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #93959f;
}

.pb-widget-list li {
    margin-bottom: 0;
    cursor: move;
    border: 1px solid #e6e9ec;
    border-radius: 3px;
    background: #fff;
    transition: all 0.2s;
    min-height: 80px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    text-align: center;
}

.pb-widget-list li:hover {
    border-color: #7f1625;
    box-shadow: 0 2px 8px rgba(127, 22, 37, 0.15);
    transform: translateY(-2px);
}

.pb-widget-list li h4 {
    margin: 0;
    padding: 12px 10px;
    font-size: 12px;
    font-weight: 500;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    color: #495157;
    line-height: 1.4;
}

.pb-widget-list li h4 .ui-icon {
    opacity: 0.5;
    font-size: 20px;
    margin-bottom: 2px;
}

.pb-widget-list li:hover h4 .ui-icon {
    opacity: 0.8;
}

/* ============================================
   PREVIEW AREA - Center (Main Canvas)
   ============================================ */
.pb-preview-area {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: #f1f3f5;
    overflow: hidden;
    position: relative;
}

.pb-preview-content {
    display: flex;
    flex-direction: column;
    height: 100%;
    overflow: hidden;
}

/* Preview Toolbar */
.pb-preview-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 16px;
    background: #fff;
    border-bottom: 1px solid #e6e9ec;
    flex-shrink: 0;
}

.pb-preview-toolbar-left,
.pb-preview-toolbar-right {
    display: flex;
    gap: 6px;
    align-items: center;
}

.pb-preview-btn {
    padding: 8px 10px;
    border: 1px solid #d4d7dc;
    background: #fff;
    border-radius: 3px;
    cursor: pointer;
    font-size: 16px;
    transition: all 0.2s;
    color: #6d7882;
}

.pb-preview-btn:hover {
    background: #f8f9fa;
    border-color: #7f1625;
    color: #7f1625;
}

.pb-preview-btn.active {
    background: #7f1625;
    border-color: #7f1625;
    color: #fff;
}

/* Preview Frame */
.pb-preview-frame-wrapper {
    flex: 1;
    overflow: hidden;
    position: relative;
}

.pb-preview-frame {
    width: 100%;
    height: 100%;
    overflow-y: auto;
    overflow-x: hidden;
    background: #f1f3f5;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
}

.pb-preview-frame::-webkit-scrollbar {
    width: 10px;
}

.pb-preview-frame::-webkit-scrollbar-track {
    background: #e9ecef;
}

.pb-preview-frame::-webkit-scrollbar-thumb {
    background: #ced4da;
    border-radius: 5px;
}

.pb-preview-inner {
    min-height: 100%;
    width: 100%;
}

.pb-preview-content-area {
    min-height: 100%;
    background: #fff;
    margin: 20px auto;
    max-width: 100%;
    box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    position: relative;
    z-index: 1;
}

/* Header, Content, Footer stacking order */
.pb-preview-content-area .pb-preview-header {
    position: relative;
    z-index: 10;
}

.pb-preview-content-area .pb-preview-page-content {
    position: relative;
    z-index: 2;
}

.pb-preview-content-area .pb-preview-footer {
    position: relative;
    z-index: 3;
}

/* Device Responsive */
.pb-preview-frame[data-device="tablet"] .pb-preview-content-area {
    max-width: 768px;
}

.pb-preview-frame[data-device="mobile"] .pb-preview-content-area {
    max-width: 375px;
}

/* ============================================
   SETTINGS PANEL - Right Sidebar
   ============================================ */
.pb-settings-panel {
    width: 0;
    background: #fff;
    border-left: 1px solid #e6e9ec;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: width 0.3s ease;
    z-index: 15;
    flex-shrink: 0;
}

.pb-settings-panel.hidden {
    width: 0;
}

.pb-settings-panel:not(.hidden) {
    width: 360px;
}

.pb-settings-panel-content {
    display: flex;
    flex-direction: column;
    height: 100%;
}

/* Settings Header */
.pb-settings-header {
    padding: 16px 20px;
    background: #fff;
    border-bottom: 1px solid #e6e9ec;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
}

.pb-settings-header h3 {
    margin: 0;
    font-size: 14px;
    font-weight: 600;
    color: #495157;
    display: flex;
    align-items: center;
    gap: 8px;
}

.pb-settings-close {
    background: transparent;
    border: none;
    color: #6d7882;
    width: 28px;
    height: 28px;
    border-radius: 3px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 18px;
}

.pb-settings-close:hover {
    background: #f1f3f5;
    color: #495157;
}

/* Settings Body */
.pb-settings-body {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
}

.pb-settings-body::-webkit-scrollbar {
    width: 6px;
}

.pb-settings-body::-webkit-scrollbar-track {
    background: transparent;
}

.pb-settings-body::-webkit-scrollbar-thumb {
    background: #d4d7dc;
    border-radius: 10px;
}

/* Settings Footer */
.pb-settings-footer {
    padding: 16px 20px;
    border-top: 1px solid #e6e9ec;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    background: #f8f9fa;
    flex-shrink: 0;
}

.pb-settings-footer .btn {
    padding: 10px 20px;
    border-radius: 3px;
    font-weight: 500;
    font-size: 13px;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}

.pb-settings-footer .btn-primary {
    background: #7f1625;
    color: #fff;
}

.pb-settings-footer .btn-primary:hover {
    background: #9a1b2e;
}

.pb-settings-footer .btn-danger {
    background: #dc3545;
    color: #fff;
}

.pb-settings-footer .btn-danger:hover {
    background: #c82333;
}

.pb-settings-footer .btn-secondary {
    background: #e6e9ec;
    color: #495157;
}

.pb-settings-footer .btn-secondary:hover {
    background: #d4d7dc;
}

/* Loading & Empty States */
.pb-settings-loading,
.pb-settings-empty,
.pb-preview-loading,
.pb-preview-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 200px;
    color: #93959f;
}

.pb-settings-loading i {
    font-size: 40px;
    color: #7f1625;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* ============================================
   BOTTOM BAR
   ============================================ */
.pb-bottom-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 16px;
    background: #fff;
    border-top: 1px solid #e6e9ec;
    font-size: 12px;
    color: #6d7882;
    flex-shrink: 0;
}

.pb-widget-count {
    font-weight: 500;
}

/* ============================================
   WIDGET INTERACTIONS
   ============================================ */
.pb-preview-content-area [data-widget-id] {
    position: relative;
    transition: box-shadow 0.2s;
    cursor: pointer;
}

.pb-preview-content-area [data-widget-id]:hover {
    box-shadow: 0 0 0 2px rgba(127, 22, 37, 0.3) inset;
}

.pb-preview-content-area [data-widget-id].pb-widget-selected {
    box-shadow: 0 0 0 2px #7f1625 inset !important;
}

.pb-preview-content-area [data-widget-id].pb-widget-selected::after {
    content: 'EDITING';
    position: absolute;
    top: 8px;
    right: 8px;
    background: #7f1625;
    color: #fff;
    padding: 4px 10px;
    border-radius: 2px;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.5px;
    z-index: 10;
}

/* Drag & Drop */
.sortable-placeholder {
    border: 2px dashed #7f1625;
    background: rgba(127, 22, 37, 0.05);
    height: 60px;
    margin: 10px 0;
    border-radius: 3px;
}

.pb-drop-hover {
    background: rgba(127, 22, 37, 0.05);
    border: 2px dashed #7f1625;
    min-height: 80px;
    border-radius: 3px;
}

/* ============================================
   RESPONSIVE
   ============================================ */
@media (max-width: 1024px) {
    .pb-sidebar {
        position: fixed;
        left: 0;
        top: 60px;
        bottom: 43px;
        z-index: 1000;
        box-shadow: 2px 0 10px rgba(0,0,0,0.1);
    }
    
    .pb-settings-panel:not(.hidden) {
        width: 320px;
    }
}

@media (max-width: 768px) {
    .pb-btn span {
        display: none;
    }
    
    .pb-page-title {
        max-width: 150px;
    }
    
    .pb-sidebar {
        width: 280px;
    }
    
    .pb-settings-panel:not(.hidden) {
        width: 100vw;
        position: fixed;
        left: 0;
        top: 60px;
        bottom: 0;
        z-index: 1001;
    }
}

/* Empty States */
.pb-sidebar-empty {
    padding: 30px 20px;
    text-align: center;
}

.pb-sidebar-empty i {
    font-size: 48px;
    color: #d4d7dc;
    margin-bottom: 12px;
}

.pb-sidebar-empty p {
    color: #93959f;
    font-size: 13px;
    margin: 0;
}
</style>

