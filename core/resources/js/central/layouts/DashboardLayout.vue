<template>
    <div class="dashboard-layout">
        <!-- Sidebar -->
        <aside class="sidebar" :class="{ 'sidebar-collapsed': sidebarCollapsed }">
            <!-- Profile Section -->
            <div class="sidebar-header">
                <div class="profile-section">
                    <div class="profile-image">
                        <img src="/assets/landlord/uploads/media-uploader/no-image.jpg" alt="Profile">
                        <span class="status-indicator online"></span>
                    </div>
                    <div v-if="!sidebarCollapsed" class="profile-info">
                        <span class="profile-name">Admin</span>
                        <span class="profile-role">super_admin</span>
                    </div>
                </div>
                
                <!-- Search -->
                <div v-if="!sidebarCollapsed" class="search-box">
                    <input v-model="searchQuery" type="text" placeholder="🔍 Search..." class="search-input">
                </div>
            </div>
            
            <!-- Menu Items -->
            <nav class="sidebar-nav">
                <ul class="nav-list">
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <router-link to="/" class="nav-link" :class="{ active: $route.name === 'dashboard' }">
                            <i class="icon">📊</i>
                            <span v-if="!sidebarCollapsed" class="nav-text">Dashboard</span>
                        </router-link>
                    </li>
                    
                    <!-- Media -->
                    <li class="nav-item">
                        <router-link to="/media" class="nav-link">
                            <i class="icon">🖼️</i>
                            <span v-if="!sidebarCollapsed" class="nav-text">Media</span>
                        </router-link>
                    </li>
                    
                    <!-- Blog -->
                    <li class="nav-item nav-dropdown" :class="{ open: openMenus.blog }">
                        <a href="#" class="nav-link" @click.prevent="toggleMenu('blog')">
                            <i class="icon">📝</i>
                            <span v-if="!sidebarCollapsed" class="nav-text">Blog</span>
                            <i v-if="!sidebarCollapsed" class="arrow" :class="{ rotated: openMenus.blog }">▼</i>
                        </a>
                        <ul v-if="openMenus.blog && !sidebarCollapsed" class="sub-menu">
                            <li><router-link to="/blog">All Blogs</router-link></li>
                            <li><router-link to="/blog/create">Add New Blog</router-link></li>
                            <li><router-link to="/blog/categories">Categories</router-link></li>
                            <li><router-link to="/blog/tags">Tags</router-link></li>
                            <li><router-link to="/blog/comments">Comments</router-link></li>
                            <li><router-link to="/blog/settings">Settings</router-link></li>
                        </ul>
                    </li>
                    
                    <!-- Pages -->
                    <li class="nav-item nav-dropdown" :class="{ open: openMenus.pages }">
                        <a href="#" class="nav-link" @click.prevent="toggleMenu('pages')">
                            <i class="icon">📄</i>
                            <span v-if="!sidebarCollapsed" class="nav-text">Pages</span>
                            <i v-if="!sidebarCollapsed" class="arrow" :class="{ rotated: openMenus.pages }">▼</i>
                        </a>
                        <ul v-if="openMenus.pages && !sidebarCollapsed" class="sub-menu">
                            <li><router-link to="/pages">All Pages</router-link></li>
                            <li><router-link to="/pages/create">Add New Page</router-link></li>
                        </ul>
                    </li>
                    
                    <!-- Packages -->
                    <li class="nav-item nav-dropdown" :class="{ open: openMenus.packages }">
                        <a href="#" class="nav-link" @click.prevent="toggleMenu('packages')">
                            <i class="icon">📦</i>
                            <span v-if="!sidebarCollapsed" class="nav-text">Packages</span>
                            <i v-if="!sidebarCollapsed" class="arrow" :class="{ rotated: openMenus.packages }">▼</i>
                        </a>
                        <ul v-if="openMenus.packages && !sidebarCollapsed" class="sub-menu">
                            <li><router-link to="/packages/create">Add New Package</router-link></li>
                            <li><router-link to="/packages">All Packages</router-link></li>
                            <li><router-link to="/packages/plans">Package Plans</router-link></li>
                        </ul>
                    </li>
                    
                    <!-- Coupons -->
                    <li class="nav-item nav-dropdown" :class="{ open: openMenus.coupons }">
                        <a href="#" class="nav-link" @click.prevent="toggleMenu('coupons')">
                            <i class="icon">🎫</i>
                            <span v-if="!sidebarCollapsed" class="nav-text">Coupons</span>
                            <i v-if="!sidebarCollapsed" class="arrow" :class="{ rotated: openMenus.coupons }">▼</i>
                        </a>
                        <ul v-if="openMenus.coupons && !sidebarCollapsed" class="sub-menu">
                            <li><router-link to="/coupons/create">Create New Coupon</router-link></li>
                            <li><router-link to="/coupons">All Coupons</router-link></li>
                        </ul>
                    </li>
                    
                    <!-- Payments -->
                    <li class="nav-item nav-dropdown" :class="{ open: openMenus.payments }">
                        <a href="#" class="nav-link" @click.prevent="toggleMenu('payments')">
                            <i class="icon">💳</i>
                            <span v-if="!sidebarCollapsed" class="nav-text">Payments</span>
                            <i v-if="!sidebarCollapsed" class="arrow" :class="{ rotated: openMenus.payments }">▼</i>
                        </a>
                        <ul v-if="openMenus.payments && !sidebarCollapsed" class="sub-menu">
                            <li><router-link to="/payments/methods">Payment Methods</router-link></li>
                            <li><router-link to="/payments/saas-settings">SAAS Settings</router-link></li>
                            <li><router-link to="/payments/currencies">Currencies</router-link></li>
                            <li><router-link to="/payments/general">General Settings</router-link></li>
                            <li><router-link to="/payments/notifications">Notification Settings</router-link></li>
                        </ul>
                    </li>
                    
                    <!-- Subscriptions -->
                    <li class="nav-item nav-dropdown" :class="{ open: openMenus.subscriptions }">
                        <a href="#" class="nav-link" @click.prevent="toggleMenu('subscriptions')">
                            <i class="icon">📮</i>
                            <span v-if="!sidebarCollapsed" class="nav-text">Subscriptions</span>
                            <i v-if="!sidebarCollapsed" class="arrow" :class="{ rotated: openMenus.subscriptions }">▼</i>
                        </a>
                        <ul v-if="openMenus.subscriptions && !sidebarCollapsed" class="sub-menu">
                            <li><router-link to="/subscriptions/subscribers">All Subscribers</router-link></li>
                            <li><router-link to="/subscriptions/stores">All Stores</router-link></li>
                            <li><router-link to="/subscriptions/payment-histories">Payment Histories</router-link></li>
                            <li><router-link to="/subscriptions/custom-domains">Custom Domains</router-link></li>
                        </ul>
                    </li>
                    
                    <!-- Support Ticket -->
                    <li class="nav-item nav-dropdown" :class="{ open: openMenus.support }">
                        <a href="#" class="nav-link" @click.prevent="toggleMenu('support')">
                            <i class="icon">🎫</i>
                            <span v-if="!sidebarCollapsed" class="nav-text">Support Ticket</span>
                            <i v-if="!sidebarCollapsed" class="arrow" :class="{ rotated: openMenus.support }">▼</i>
                        </a>
                        <ul v-if="openMenus.support && !sidebarCollapsed" class="sub-menu">
                            <li><router-link to="/support/create">Create Ticket</router-link></li>
                            <li><router-link to="/support">All Tickets</router-link></li>
                            <li><router-link to="/support/categories">Categories</router-link></li>
                        </ul>
                    </li>
                    
                    <!-- Appearances -->
                    <li class="nav-item nav-dropdown" :class="{ open: openMenus.appearances }">
                        <a href="#" class="nav-link" @click.prevent="toggleMenu('appearances')">
                            <i class="icon">🎨</i>
                            <span v-if="!sidebarCollapsed" class="nav-text">Appearances</span>
                            <i v-if="!sidebarCollapsed" class="arrow" :class="{ rotated: openMenus.appearances }">▼</i>
                        </a>
                        <ul v-if="openMenus.appearances && !sidebarCollapsed" class="sub-menu">
                            <li><router-link to="/appearances/themes">Themes</router-link></li>
                            <li><router-link to="/appearances/menus">Menus</router-link></li>
                            <li><router-link to="/appearances/theme-options">Theme Options</router-link></li>
                            <li><router-link to="/appearances/general">General Settings</router-link></li>
                            <li><router-link to="/appearances/widgets">Widgets</router-link></li>
                        </ul>
                    </li>
                    
                    <!-- Plugins -->
                    <li class="nav-item">
                        <router-link to="/plugins" class="nav-link">
                            <i class="icon">🔌</i>
                            <span v-if="!sidebarCollapsed" class="nav-text">Plugins</span>
                        </router-link>
                    </li>
                    
                    <!-- Settings -->
                    <li class="nav-item nav-dropdown" :class="{ open: openMenus.settings }">
                        <a href="#" class="nav-link" @click.prevent="toggleMenu('settings')">
                            <i class="icon">⚙️</i>
                            <span v-if="!sidebarCollapsed" class="nav-text">Settings</span>
                            <i v-if="!sidebarCollapsed" class="arrow" :class="{ rotated: openMenus.settings }">▼</i>
                        </a>
                        <ul v-if="openMenus.settings && !sidebarCollapsed" class="sub-menu">
                            <li><router-link to="/settings/general">General Settings</router-link></li>
                            <li><router-link to="/settings/email">Email settings</router-link></li>
                            <li><router-link to="/settings/email-templates">Email Templates</router-link></li>
                            <li><router-link to="/settings/languages">Languages</router-link></li>
                            <li><router-link to="/settings/media">Media settings</router-link></li>
                            <li><router-link to="/settings/seo">SEO settings</router-link></li>
                        </ul>
                    </li>
                    
                    <!-- Users -->
                    <li class="nav-item nav-dropdown" :class="{ open: openMenus.users }">
                        <a href="#" class="nav-link" @click.prevent="toggleMenu('users')">
                            <i class="icon">👥</i>
                            <span v-if="!sidebarCollapsed" class="nav-text">Users</span>
                            <i v-if="!sidebarCollapsed" class="arrow" :class="{ rotated: openMenus.users }">▼</i>
                        </a>
                        <ul v-if="openMenus.users && !sidebarCollapsed" class="sub-menu">
                            <li><router-link to="/users">Users</router-link></li>
                            <li><router-link to="/users/roles">Roles</router-link></li>
                            <li><router-link to="/users/permissions">Permissions</router-link></li>
                            <li><router-link to="/users/activity-logs">Activity Logs</router-link></li>
                            <li><router-link to="/users/login-activity">Login activity</router-link></li>
                        </ul>
                    </li>
                    
                    <!-- System -->
                    <li class="nav-item nav-dropdown" :class="{ open: openMenus.system }">
                        <a href="#" class="nav-link" @click.prevent="toggleMenu('system')">
                            <i class="icon">🖥️</i>
                            <span v-if="!sidebarCollapsed" class="nav-text">System</span>
                            <i v-if="!sidebarCollapsed" class="arrow" :class="{ rotated: openMenus.system }">▼</i>
                        </a>
                        <ul v-if="openMenus.system && !sidebarCollapsed" class="sub-menu">
                            <li><router-link to="/system/sitemap">Sitemap</router-link></li>
                            <li><router-link to="/system/update">Update</router-link></li>
                            <li><router-link to="/system/backups">Backups</router-link></li>
                        </ul>
                    </li>
                    
                    <!-- Tenants -->
                    <li class="nav-item">
                        <router-link to="/tenants" class="nav-link">
                            <i class="icon">🏢</i>
                            <span v-if="!sidebarCollapsed" class="nav-text">Tenants</span>
                        </router-link>
                    </li>
                </ul>
            </nav>
            
            <!-- Collapse Button -->
            <button class="collapse-btn" @click="toggleSidebar">
                <i v-if="sidebarCollapsed">→</i>
                <i v-else>←</i>
            </button>
        </aside>
        
        <!-- Main Content -->
        <div class="main-content" :class="{ 'content-expanded': sidebarCollapsed }">
            <!-- Top Bar -->
            <header class="top-bar">
                <div class="top-bar-left">
                    <h1 class="page-title">{{ currentPageTitle }}</h1>
                </div>
                <div class="top-bar-right">
                    <button class="btn-logout" @click="handleLogout">
                        🚪 Logout
                    </button>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="page-content">
                <router-view />
            </main>
        </div>
    </div>
</template>

<script>
import { ref, computed } from 'vue'
import { useRoute } from 'vue-router'

export default {
    name: 'DashboardLayout',
    setup() {
        const route = useRoute()
        const searchQuery = ref('')
        const sidebarCollapsed = ref(false)
        const openMenus = ref({
            blog: false,
            pages: false,
            packages: false,
            coupons: false,
            payments: false,
            subscriptions: false,
            support: false,
            appearances: false,
            settings: false,
            users: false,
            system: false
        })
        
        const currentPageTitle = computed(() => {
            return route.meta.title || 'Dashboard'
        })
        
        const toggleSidebar = () => {
            sidebarCollapsed.value = !sidebarCollapsed.value
        }
        
        const toggleMenu = (menu) => {
            openMenus.value[menu] = !openMenus.value[menu]
        }
        
        const handleLogout = () => {
            localStorage.removeItem('central_token')
            window.location.href = '/admin-home'
        }
        
        return {
            searchQuery,
            sidebarCollapsed,
            openMenus,
            currentPageTitle,
            toggleSidebar,
            toggleMenu,
            handleLogout
        }
    }
}
</script>

<style scoped>
.dashboard-layout {
    display: flex;
    min-height: 100vh;
    background: #f5f7fa;
}

/* Sidebar */
.sidebar {
    width: 280px;
    background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
    color: white;
    display: flex;
    flex-direction: column;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
    transition: width 0.3s ease;
    position: fixed;
    height: 100vh;
    overflow-y: auto;
    z-index: 1000;
}

.sidebar.sidebar-collapsed {
    width: 70px;
}

.sidebar-header {
    padding: 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.profile-section {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.profile-image {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    position: relative;
    flex-shrink: 0;
}

.profile-image img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

.status-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    position: absolute;
    bottom: 0;
    right: 0;
    border: 2px solid #1e293b;
}

.status-indicator.online {
    background: #10b981;
}

.profile-info {
    margin-left: 12px;
    display: flex;
    flex-direction: column;
}

.profile-name {
    font-weight: 600;
    font-size: 14px;
}

.profile-role {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.6);
}

.search-box {
    margin-top: 10px;
}

.search-input {
    width: 100%;
    padding: 10px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 6px;
    background: rgba(255, 255, 255, 0.05);
    color: white;
    font-size: 13px;
}

.search-input::placeholder {
    color: rgba(255, 255, 255, 0.4);
}

/* Navigation */
.sidebar-nav {
    flex: 1;
    padding: 10px 0;
    overflow-y: auto;
}

.nav-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.nav-item {
    margin-bottom: 2px;
}

.nav-link {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    transition: all 0.2s;
    border-left: 3px solid transparent;
}

.nav-link:hover,
.nav-link.active {
    background: rgba(59, 130, 246, 0.15);
    color: white;
    border-left-color: #3b82f6;
}

.sidebar-collapsed .nav-link {
    justify-content: center;
    padding: 12px;
}

.icon {
    font-size: 20px;
    min-width: 24px;
    margin-right: 12px;
}

.sidebar-collapsed .icon {
    margin-right: 0;
}

.nav-text {
    font-size: 14px;
    font-weight: 500;
}

.arrow {
    margin-left: auto;
    font-size: 10px;
    transition: transform 0.2s;
}

.arrow.rotated {
    transform: rotate(-180deg);
}

.sub-menu {
    list-style: none;
    padding: 0;
    margin: 0;
    background: rgba(0, 0, 0, 0.2);
}

.sub-menu li {
    margin: 0;
}

.sub-menu a {
    display: block;
    padding: 10px 20px 10px 60px;
    color: rgba(255, 255, 255, 0.6);
    text-decoration: none;
    font-size: 13px;
    transition: all 0.2s;
}

.sub-menu a:hover,
.sub-menu a.router-link-active {
    background: rgba(59, 130, 246, 0.1);
    color: white;
}

/* Collapse Button */
.collapse-btn {
    position: absolute;
    bottom: 20px;
    right: 20px;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    border: 1px solid rgba(255, 255, 255, 0.2);
    background: rgba(59, 130, 246, 0.2);
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.collapse-btn:hover {
    background: rgba(59, 130, 246, 0.3);
}

/* Main Content */
.main-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    margin-left: 280px;
    transition: margin-left 0.3s ease;
}

.main-content.content-expanded {
    margin-left: 70px;
}

/* Top Bar */
.top-bar {
    background: white;
    padding: 20px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    border-bottom: 1px solid #e5e7eb;
}

.page-title {
    font-size: 24px;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}

.btn-logout {
    background: #ef4444;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    transition: background 0.2s;
}

.btn-logout:hover {
    background: #dc2626;
}

/* Page Content */
.page-content {
    flex: 1;
    padding: 30px;
    overflow-y: auto;
}

/* Scrollbar */
.sidebar-nav::-webkit-scrollbar {
    width: 6px;
}

.sidebar-nav::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
}

.sidebar-nav::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 3px;
}

.sidebar-nav::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.3);
}
</style>
