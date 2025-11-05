<style>
/* ============================================
   Modern PageBuilder UI - Redesigned
   ============================================ */

/* Main Wrapper */
.pagebuilder-elementor-wrapper {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    flex-direction: column;
    background: linear-gradient(135deg, #7f1625 0%, #5f101c 100%);
    background-size: cover;
    z-index: 9999;
    overflow: hidden;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
}

/* Top Bar - Modern Glassmorphism */
.pb-top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 24px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    z-index: 100;
}

.pb-top-left {
    display: flex;
    gap: 12px;
    align-items: center;
}

.pb-top-right {
    display: flex;
    gap: 16px;
    align-items: center;
}

/* Modern Buttons */
.pb-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border: none;
    background: #fff;
    border-radius: 12px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    color: #1a202c;
}

.pb-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
}

.pb-btn:active {
    transform: translateY(0);
}

.pb-btn-save {
    background: linear-gradient(135deg, #7f1625 0%, #5f101c 100%);
    color: #fff;
    font-weight: 600;
}

.pb-btn-save:hover {
    background: linear-gradient(135deg, #9a1b2e 0%, #7f1625 100%);
    box-shadow: 0 8px 24px rgba(127, 22, 37, 0.5);
}

.pb-btn-preview,
.pb-btn-settings {
    background: #f7fafc;
    border: 1px solid #e2e8f0;
}

.pb-btn-link {
    background: transparent;
    color: #7f1625;
    box-shadow: none;
}

.pb-btn-link:hover {
    background: rgba(127, 22, 37, 0.1);
}

.pb-status {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 500;
    color: #4a5568;
    padding: 8px 16px;
    background: #f7fafc;
    border-radius: 20px;
}

.pb-status:before {
    content: '';
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #48bb78;
    box-shadow: 0 0 0 3px rgba(72, 187, 120, 0.2);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        box-shadow: 0 0 0 3px rgba(72, 187, 120, 0.2);
    }
    50% {
        box-shadow: 0 0 0 6px rgba(72, 187, 120, 0.1);
    }
}

/* Main Content */
.pb-main-content {
    display: flex;
    flex: 1;
    overflow: hidden;
    position: relative;
    gap: 0;
}

/* Settings Panel - Fixed Left Side (Collapsible) */
.pb-settings-panel {
    width: 0;
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(40px);
    -webkit-backdrop-filter: blur(40px);
    box-shadow: 2px 0 16px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s;
    z-index: 10;
    flex-shrink: 0;
    opacity: 0;
}

.pb-settings-panel.hidden {
    width: 0;
    opacity: 0;
    pointer-events: none;
}

.pb-settings-panel:not(.hidden) {
    width: 380px;
    opacity: 1;
    pointer-events: auto;
}

/* Toggle Button for Settings */
.pb-settings-toggle-btn {
    position: fixed;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    background: linear-gradient(135deg, #7f1625 0%, #5f101c 100%);
    color: #fff;
    border: none;
    padding: 12px 8px;
    border-radius: 0 8px 8px 0;
    cursor: pointer;
    z-index: 1001;
    box-shadow: 2px 0 12px rgba(127, 22, 37, 0.3);
    transition: all 0.3s;
    font-size: 20px;
    line-height: 1;
}

.pb-settings-toggle-btn:hover {
    padding-left: 12px;
    box-shadow: 4px 0 20px rgba(127, 22, 37, 0.5);
}

.pb-settings-panel:not(.hidden) ~ .pb-settings-toggle-btn {
    left: 380px;
}

.pb-settings-toggle-btn i {
    transition: transform 0.3s;
}

.pb-settings-panel:not(.hidden) ~ .pb-settings-toggle-btn i {
    transform: rotate(180deg);
}

/* Sidebar - Modern Card Style */
.pb-sidebar {
    width: 320px;
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 4px 0 24px rgba(0, 0, 0, 0.08);
    border-radius: 0 24px 24px 0;
    margin: 16px 0 16px 16px;
}

.pb-sidebar.minimized {
    width: 0;
    margin-left: 0;
    opacity: 0;
    pointer-events: none;
}

.pb-sidebar-content {
    display: flex;
    flex-direction: column;
    height: 100%;
    overflow: hidden;
    padding: 8px;
}

/* Search Bar */
.pb-sidebar-search {
    padding: 16px;
    border-bottom: none;
    margin-bottom: 8px;
}

.pb-sidebar-search .form-group {
    position: relative;
    margin: 0;
}

.pb-sidebar-search input {
    width: 100%;
    padding: 12px 12px 12px 44px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 14px;
    transition: all 0.3s;
    background: #f7fafc;
}

.pb-sidebar-search input:focus {
    outline: none;
    border-color: #7f1625;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(127, 22, 37, 0.1);
}

.pb-sidebar-search .mdi-magnify {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #a0aec0;
    font-size: 20px;
}

/* Tabs - Modern Pills */
.pb-sidebar-tabs {
    border-bottom: none;
    padding: 0 16px 16px;
}

.pb-sidebar-tabs .nav-tabs {
    border: none;
    margin: 0;
    display: flex;
    gap: 8px;
    background: #f7fafc;
    padding: 6px;
    border-radius: 12px;
}

.pb-sidebar-tabs .nav-link {
    border: none;
    border-radius: 8px;
    padding: 10px 16px;
    color: #4a5568;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 500;
    transition: all 0.3s;
    flex: 1;
    justify-content: center;
}

.pb-sidebar-tabs .nav-link i {
    font-size: 18px;
    flex-shrink: 0;
}

.pb-sidebar-tabs .nav-link:hover {
    background: rgba(127, 22, 37, 0.1);
    color: #7f1625;
}

.pb-sidebar-tabs .nav-link.active {
    background: linear-gradient(135deg, #7f1625 0%, #5f101c 100%);
    color: #fff;
    box-shadow: 0 4px 12px rgba(127, 22, 37, 0.4);
    border-bottom: none;
}

/* Widgets List */
.pb-sidebar-widgets {
    flex: 1;
    overflow-y: auto;
    padding: 0 8px 8px;
}

.pb-sidebar-widgets::-webkit-scrollbar {
    width: 6px;
}

.pb-sidebar-widgets::-webkit-scrollbar-track {
    background: transparent;
}

.pb-sidebar-widgets::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 10px;
}

.pb-sidebar-widgets::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
}

.pb-widget-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.pb-widget-list li {
    margin-bottom: 10px;
    cursor: move;
    border: 2px solid transparent;
    border-radius: 12px;
    background: #fff;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
}

.pb-widget-list li:hover {
    border-color: #7f1625;
    box-shadow: 0 8px 24px rgba(127, 22, 37, 0.25);
    transform: translateY(-2px);
}

.pb-widget-list li h4 {
    margin: 0;
    padding: 14px 16px;
    font-size: 14px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
    color: #2d3748;
}

.pb-widget-list li h4 .ui-icon {
    flex-shrink: 0;
    opacity: 0.4;
    transition: opacity 0.3s;
}

.pb-widget-list li:hover h4 .ui-icon {
    opacity: 0.7;
}

/* Preview Area - Full Width Modern */
.pb-preview-area {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: #fff;
    overflow: hidden;
    position: relative;
    margin: 16px 16px 16px 8px;
    border-radius: 24px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
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
    padding: 12px 20px;
    background: rgba(247, 250, 252, 0.8);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid #e2e8f0;
    flex-shrink: 0;
}

.pb-preview-toolbar-left,
.pb-preview-toolbar-right {
    display: flex;
    gap: 8px;
    align-items: center;
}

.pb-preview-btn {
    padding: 8px 12px;
    border: none;
    background: #fff;
    border-radius: 8px;
    cursor: pointer;
    font-size: 18px;
    transition: all 0.3s;
    color: #4a5568;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.pb-preview-btn:hover {
    background: #7f1625;
    color: #fff;
    transform: scale(1.1);
}

.pb-preview-btn.active {
    background: #7f1625;
    color: #fff;
}

.pb-preview-frame-wrapper {
    flex: 1;
    overflow: hidden;
    position: relative;
}

.pb-preview-frame {
    width: 100%;
    height: 100%;
    overflow-y: auto !important;
    overflow-x: hidden;
    background: #f7fafc;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
}

.pb-preview-inner {
    min-height: 100%;
    width: 100%;
}

.pb-preview-frame::-webkit-scrollbar {
    width: 10px;
}

.pb-preview-frame::-webkit-scrollbar-track {
    background: #f1f5f9;
}

.pb-preview-frame::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 10px;
    border: 2px solid #f1f5f9;
}

.pb-preview-frame::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
}

.pb-preview-content-area {
    min-height: 100%;
    background: #fff;
    margin: 20px;
    border-radius: 16px;
    box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.05);
    padding-bottom: 50px;
}

/* Device Frames */
.pb-preview-frame[data-device="desktop"] .pb-preview-content-area {
    max-width: 100%;
}

.pb-preview-frame[data-device="tablet"] .pb-preview-content-area {
    max-width: 768px;
    margin: 20px auto;
}

.pb-preview-frame[data-device="mobile"] .pb-preview-content-area {
    max-width: 375px;
    margin: 20px auto;
}

.pb-settings-panel-content {
    display: flex;
    flex-direction: column;
    height: 100%;
}

/* Settings Header */
.pb-settings-header {
    padding: 20px 24px;
    background: linear-gradient(135deg, #7f1625 0%, #5f101c 100%);
    color: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
}

.pb-settings-header h3 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.pb-settings-header h3 i {
    font-size: 20px;
}

.pb-settings-close {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: #fff;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 18px;
}

.pb-settings-close:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
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
    background: #cbd5e0;
    border-radius: 10px;
}

/* Settings Footer */
.pb-settings-footer {
    padding: 16px 20px;
    border-top: 1px solid #e2e8f0;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    background: #f7fafc;
    flex-shrink: 0;
}

.pb-settings-footer .btn {
    padding: 8px 20px;
    border-radius: 8px;
    font-weight: 500;
    font-size: 14px;
    transition: all 0.3s;
}

.pb-settings-footer .btn-primary {
    background: linear-gradient(135deg, #7f1625 0%, #5f101c 100%);
    border: none;
    color: #fff;
}

.pb-settings-footer .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(127, 22, 37, 0.5);
}

.pb-settings-footer .btn-danger {
    background: #f56565;
    border: none;
    color: #fff;
}

.pb-settings-footer .btn-danger:hover {
    background: #e53e3e;
}

.pb-settings-footer .btn-secondary {
    background: #e2e8f0;
    border: none;
    color: #4a5568;
}

.pb-settings-footer .btn-secondary:hover {
    background: #cbd5e0;
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
    min-height: 300px;
    color: #a0aec0;
}

.pb-settings-loading i,
.pb-preview-loading i {
    font-size: 48px;
    animation: spin 1s linear infinite;
    color: #7f1625;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Bottom Bar */
.pb-bottom-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 24px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-top: 1px solid rgba(0, 0, 0, 0.05);
    font-size: 13px;
    font-weight: 500;
    color: #4a5568;
}

.pb-widget-count {
    padding: 6px 16px;
    background: #f7fafc;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
}

.pb-btn-clear {
    background: transparent;
    color: #e53e3e;
    border: 1px solid #e53e3e;
}

.pb-btn-clear:hover {
    background: #e53e3e;
    color: #fff;
}

/* Widget Hover & Selection - Improved UX */
.pb-preview-content-area [data-widget-id] {
    position: relative;
    transition: all 0.2s ease;
    outline: 2px solid transparent;
    outline-offset: 2px;
}

.pb-preview-content-area [data-widget-id]:hover {
    outline-color: rgba(127, 22, 37, 0.4);
    cursor: pointer;
}

.pb-preview-content-area [data-widget-id].pb-widget-selected {
    outline-color: #7f1625;
    outline-width: 2px;
    outline-style: solid;
    position: relative;
}

.pb-preview-content-area [data-widget-id].pb-widget-selected::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(127, 22, 37, 0.05);
    pointer-events: none;
    z-index: 1;
}

.pb-preview-content-area [data-widget-id].pb-widget-selected::after {
    content: '✓ Editing';
    position: absolute;
    top: 8px;
    left: 8px;
    background: linear-gradient(135deg, #7f1625 0%, #5f101c 100%);
    color: #fff;
    padding: 4px 12px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    z-index: 2;
    box-shadow: 0 2px 8px rgba(127, 22, 37, 0.4);
}

/* Drag & Drop */
.sortable-placeholder {
    border: 3px dashed #7f1625;
    background: linear-gradient(135deg, rgba(127, 22, 37, 0.05) 0%, rgba(95, 16, 28, 0.05) 100%);
    height: 60px;
    margin: 12px 0;
    border-radius: 12px;
}

.ui-sortable-helper {
    opacity: 0.9;
    box-shadow: 0 12px 48px rgba(0, 0, 0, 0.2);
    transform: rotate(3deg);
}

.pb-drop-hover {
    background: rgba(127, 22, 37, 0.08);
    border: 3px dashed #7f1625;
    min-height: 100px;
    border-radius: 12px;
}

/* Responsive */
@media (max-width: 1400px) {
    .pb-settings-panel:not(.hidden) {
        width: 340px;
    }
    .pb-settings-panel:not(.hidden) ~ .pb-settings-toggle-btn {
        left: 340px;
    }
}

@media (max-width: 1200px) {
    .pb-sidebar {
        width: 280px;
    }
    .pb-settings-panel:not(.hidden) {
        width: 320px;
    }
    .pb-settings-panel:not(.hidden) ~ .pb-settings-toggle-btn {
        left: 320px;
    }
}

/* iPad and Tablets (768px - 1024px) */
@media (max-width: 1024px) {
    .pb-sidebar {
        width: 260px;
    }
    
    .pb-settings-panel {
        position: fixed;
        left: 0;
        top: 0;
        bottom: 0;
        z-index: 1002;
        box-shadow: 4px 0 24px rgba(0, 0, 0, 0.2);
    }
    
    .pb-settings-panel:not(.hidden) {
        width: 340px;
    }
    
    .pb-settings-toggle-btn {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .pb-settings-panel:not(.hidden) ~ .pb-settings-toggle-btn {
        left: 340px;
    }
    
    /* Hide sidebar when settings is open on tablets */
    .pb-settings-panel:not(.hidden) ~ .pb-sidebar {
        display: none;
    }
}

@media (max-width: 768px) {
    .pb-settings-panel {
        position: fixed;
        left: 0;
        top: 0;
        bottom: 0;
        z-index: 1003;
    }
    
    .pb-settings-panel:not(.hidden) {
        width: 85vw;
    }
    
    .pb-settings-toggle-btn {
        top: 60px;
        padding: 10px 6px;
        font-size: 18px;
    }
    
    .pb-settings-panel:not(.hidden) ~ .pb-settings-toggle-btn {
        left: 85vw;
    }
    
    .pb-sidebar {
        position: fixed;
        top: 70px;
        left: 0;
        bottom: 70px;
        width: 280px;
        z-index: 1001;
        box-shadow: 4px 0 24px rgba(0, 0, 0, 0.15);
        transform: translateX(-100%);
        transition: transform 0.3s;
    }
    
    .pb-sidebar.show {
        transform: translateX(0);
    }
    
    .pb-top-bar {
        flex-wrap: wrap;
        padding: 12px 16px;
    }
    
    .pb-btn span {
        display: none;
    }
    
    .pb-btn {
        padding: 8px 12px;
    }
    
    /* Hide settings when sidebar is shown on mobile */
    .pb-sidebar.show ~ .pb-settings-panel,
    .pb-sidebar.show ~ .pb-settings-toggle-btn {
        display: none;
    }
}

/* RTL Support */
[dir="rtl"] .pb-settings-panel {
    left: auto;
    right: 0;
}

[dir="rtl"] .pb-settings-toggle-btn {
    left: auto;
    right: 0;
    border-radius: 8px 0 0 8px;
}

[dir="rtl"] .pb-settings-panel:not(.hidden) ~ .pb-settings-toggle-btn {
    left: auto;
    right: 380px;
}

[dir="rtl"] .pb-settings-toggle-btn i {
    transform: rotate(180deg);
}

[dir="rtl"] .pb-settings-panel:not(.hidden) ~ .pb-settings-toggle-btn i {
    transform: rotate(0deg);
}

[dir="rtl"] .pb-preview-content-area [data-widget-id].pb-widget-selected::after {
    left: auto;
    right: 8px;
}

/* Empty State */
.pb-sidebar-empty {
    padding: 40px 20px;
    text-align: center;
}

.pb-sidebar-empty i {
    font-size: 56px;
    color: #cbd5e0;
    margin-bottom: 16px;
}

.pb-sidebar-empty p {
    color: #a0aec0;
    font-size: 14px;
    margin: 0;
}

/* Minimize Button Animation */
.pb-btn-minimize {
    transition: all 0.3s;
}

.pb-sidebar.minimized ~ * .pb-btn-minimize i {
    transform: rotate(180deg);
}
</style>
