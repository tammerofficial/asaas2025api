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
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="search-icon">
                        <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd"/>
                    </svg>
                    <input v-model="searchQuery" type="text" placeholder="Search..." class="search-input">
                </div>
            </div>
            
            <!-- Menu Items -->
            <nav class="sidebar-nav">
                <ul class="nav-list">
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <router-link to="/" class="nav-link" :class="{ active: $route.name === 'dashboard' }">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="nav-icon">
                                <path fill-rule="evenodd" d="M3 6a3 3 0 013-3h2.25a3 3 0 013 3v2.25a3 3 0 01-3 3H6a3 3 0 01-3-3V6zm9.75 0a3 3 0 013-3H18a3 3 0 013 3v2.25a3 3 0 01-3 3h-2.25a3 3 0 01-3-3V6zM3 15.75a3 3 0 013-3h2.25a3 3 0 013 3V18a3 3 0 01-3 3H6a3 3 0 01-3-3v-2.25zm9.75 0a3 3 0 013-3H18a3 3 0 013 3V18a3 3 0 01-3 3h-2.25a3 3 0 01-3-3v-2.25z" clip-rule="evenodd"/>
                            </svg>
                            <span v-if="!sidebarCollapsed" class="nav-text">Dashboard</span>
                        </router-link>
                    </li>
                    
                    <!-- Media -->
                    <li class="nav-item">
                        <router-link to="/media" class="nav-link">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="nav-icon">
                                <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd"/>
                            </svg>
                            <span v-if="!sidebarCollapsed" class="nav-text">Media</span>
                        </router-link>
                    </li>
                    
                    <!-- Blog -->
                    <li class="nav-item nav-dropdown" :class="{ open: openMenus.blog }">
                        <div class="nav-link-wrapper">
                            <router-link to="/blog" class="nav-link">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="nav-icon">
                                    <path fill-rule="evenodd" d="M4.125 3C3.089 3 2.25 3.84 2.25 4.875V18a3 3 0 003 3h15a3 3 0 01-3-3V4.875C17.25 3.839 16.41 3 15.375 3H4.125zM12 9.75a.75.75 0 000 1.5h1.5a.75.75 0 000-1.5H12zm-.75-2.25a.75.75 0 01.75-.75h1.5a.75.75 0 010 1.5H12a.75.75 0 01-.75-.75zM6 12.75a.75.75 0 000 1.5h7.5a.75.75 0 000-1.5H6zm-.75 3.75a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5H6a.75.75 0 01-.75-.75zM6 6.75a.75.75 0 00-.75.75v3c0 .414.336.75.75.75h3a.75.75 0 00.75-.75v-3A.75.75 0 009 6.75H6z" clip-rule="evenodd"/>
                                    <path d="M18.75 6.75h1.875c.621 0 1.125.504 1.125 1.125V18a1.5 1.5 0 01-3 0V6.75z"/>
                                </svg>
                                <span v-if="!sidebarCollapsed" class="nav-text">Blog</span>
                            </router-link>
                            <button v-if="!sidebarCollapsed" class="nav-dropdown-toggle" @click.stop="toggleMenu('blog')" type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="arrow" :class="{ rotated: openMenus.blog }">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
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
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="nav-icon">
                                <path fill-rule="evenodd" d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0016.5 9h-1.875a1.875 1.875 0 01-1.875-1.875V5.25A3.75 3.75 0 009 1.5H5.625zM7.5 15a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5A.75.75 0 017.5 15zm.75 2.25a.75.75 0 000 1.5H12a.75.75 0 000-1.5H8.25z" clip-rule="evenodd"/>
                                <path d="M12.971 1.816A5.23 5.23 0 0114.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 013.434 1.279 9.768 9.768 0 00-6.963-6.963z"/>
                            </svg>
                            <span v-if="!sidebarCollapsed" class="nav-text">Pages</span>
                            <svg v-if="!sidebarCollapsed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="arrow" :class="{ rotated: openMenus.pages }">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                        <ul v-if="openMenus.pages && !sidebarCollapsed" class="sub-menu">
                            <li><router-link to="/pages">All Pages</router-link></li>
                            <li><router-link to="/pages/create">Add New Page</router-link></li>
                        </ul>
                    </li>
                    
                    <!-- Packages -->
                    <li class="nav-item nav-dropdown" :class="{ open: openMenus.packages }">
                        <a href="#" class="nav-link" @click.prevent="toggleMenu('packages')">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="nav-icon">
                                <path d="M3.375 3C2.339 3 1.5 3.84 1.5 4.875v.75c0 1.036.84 1.875 1.875 1.875h17.25c1.035 0 1.875-.84 1.875-1.875v-.75C22.5 3.839 21.66 3 20.625 3H3.375z"/>
                                <path fill-rule="evenodd" d="M3.087 9l.54 9.176A3 3 0 006.62 21h10.757a3 3 0 002.995-2.824L20.913 9H3.087zm6.163 3.75A.75.75 0 0110 12h4a.75.75 0 010 1.5h-4a.75.75 0 01-.75-.75z" clip-rule="evenodd"/>
                            </svg>
                            <span v-if="!sidebarCollapsed" class="nav-text">Packages</span>
                            <svg v-if="!sidebarCollapsed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="arrow" :class="{ rotated: openMenus.packages }">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                            </svg>
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
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="nav-icon">
                                <path fill-rule="evenodd" d="M1.5 6.375c0-1.036.84-1.875 1.875-1.875h17.25c1.035 0 1.875.84 1.875 1.875v3.026a.75.75 0 01-.375.65 2.249 2.249 0 000 3.898.75.75 0 01.375.65v3.026c0 1.035-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 011.5 17.625v-3.026a.75.75 0 01.374-.65 2.249 2.249 0 000-3.898.75.75 0 01-.374-.65V6.375zm15-1.125a.75.75 0 01.75.75v.75a.75.75 0 01-1.5 0V6a.75.75 0 01.75-.75zm.75 4.5a.75.75 0 00-1.5 0v.75a.75.75 0 001.5 0v-.75zm-.75 3a.75.75 0 01.75.75v.75a.75.75 0 01-1.5 0v-.75a.75.75 0 01.75-.75zm.75 4.5a.75.75 0 00-1.5 0V18a.75.75 0 001.5 0v-.75zM6 12a.75.75 0 01.75-.75H12a.75.75 0 010 1.5H6.75A.75.75 0 016 12zm.75 2.25a.75.75 0 000 1.5h3a.75.75 0 000-1.5h-3z" clip-rule="evenodd"/>
                            </svg>
                            <span v-if="!sidebarCollapsed" class="nav-text">Coupons</span>
                            <svg v-if="!sidebarCollapsed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="arrow" :class="{ rotated: openMenus.coupons }">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                        <ul v-if="openMenus.coupons && !sidebarCollapsed" class="sub-menu">
                            <li><router-link to="/coupons/create">Create New Coupon</router-link></li>
                            <li><router-link to="/coupons">All Coupons</router-link></li>
                        </ul>
                    </li>
                    
                    <!-- Orders -->
                    <li class="nav-item">
                        <router-link to="/orders" class="nav-link">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="nav-icon">
                                <path fill-rule="evenodd" d="M7.502 6h7.128A3.375 3.375 0 0118 9.375v9.375a3 3 0 01-3 3H5.25a3 3 0 01-3-3V9.375C2.25 8.339 3.09 7.5 4.125 7.5h4.38c.276-1.496 1.564-2.625 3.12-2.625 1.555 0 2.843 1.129 3.12 2.625h4.38c1.035 0 1.875.84 1.875 1.875V12a.75.75 0 01-1.5 0V9.375c0-.207-.168-.375-.375-.375h-4.38c-.276 1.496-1.564 2.625-3.12 2.625-1.556 0-2.844-1.129-3.12-2.625H4.125A.375.375 0 003.75 9.375v9.375a1.5 1.5 0 001.5 1.5h9.75a1.5 1.5 0 001.5-1.5V12a.75.75 0 011.5 0v6.75a3 3 0 01-3 3H5.25a3 3 0 01-3-3V9.375C2.25 7.839 3.09 6.75 4.125 6.75h.38c.276-1.496 1.564-2.625 3.12-2.625 1.555 0 2.843 1.129 3.12 2.625h.38c1.035 0 1.875.84 1.875 1.875zM8.625 4.5a1.875 1.875 0 113.75 0c0 .207-.168.375-.375.375h-3a.375.375 0 01-.375-.375z" clip-rule="evenodd"/>
                            </svg>
                            <span v-if="!sidebarCollapsed" class="nav-text">Orders</span>
                        </router-link>
                    </li>
                    
                    <!-- Payments -->
                    <li class="nav-item nav-dropdown" :class="{ open: openMenus.payments }">
                        <a href="#" class="nav-link" @click.prevent="toggleMenu('payments')">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="nav-icon">
                                <path d="M4.5 3.75a3 3 0 00-3 3v.75h21v-.75a3 3 0 00-3-3h-15z"/>
                                <path fill-rule="evenodd" d="M22.5 9.75h-21v7.5a3 3 0 003 3h15a3 3 0 003-3v-7.5zm-18 3.75a.75.75 0 01.75-.75h6a.75.75 0 010 1.5h-6a.75.75 0 01-.75-.75zm.75 2.25a.75.75 0 000 1.5h3a.75.75 0 000-1.5h-3z" clip-rule="evenodd"/>
                            </svg>
                            <span v-if="!sidebarCollapsed" class="nav-text">Payments</span>
                            <svg v-if="!sidebarCollapsed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="arrow" :class="{ rotated: openMenus.payments }">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                        <ul v-if="openMenus.payments && !sidebarCollapsed" class="sub-menu">
                            <li><router-link to="/payments">All Payments</router-link></li>
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
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="nav-icon">
                                <path fill-rule="evenodd" d="M5.478 5.559A1.5 1.5 0 016.912 4.5H9A.75.75 0 009 3H6.912a3 3 0 00-2.868 2.118l-2.411 7.838a3 3 0 00-.133.882V18a3 3 0 003 3h15a3 3 0 003-3v-4.162c0-.299-.045-.596-.133-.882l-2.412-7.838A3 3 0 0017.088 3H15a.75.75 0 000 1.5h2.088a1.5 1.5 0 011.434 1.059l2.213 7.191H17.89a3 3 0 00-2.684 1.658l-.256.513a1.5 1.5 0 01-1.342.829h-3.218a1.5 1.5 0 01-1.342-.83l-.256-.512a3 3 0 00-2.684-1.658H3.265l2.213-7.191z" clip-rule="evenodd"/>
                                <path fill-rule="evenodd" d="M12 2.25a.75.75 0 01.75.75v6.44l1.72-1.72a.75.75 0 111.06 1.06l-3 3a.75.75 0 01-1.06 0l-3-3a.75.75 0 011.06-1.06l1.72 1.72V3a.75.75 0 01.75-.75z" clip-rule="evenodd"/>
                            </svg>
                            <span v-if="!sidebarCollapsed" class="nav-text">Subscriptions</span>
                            <svg v-if="!sidebarCollapsed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="arrow" :class="{ rotated: openMenus.subscriptions }">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                            </svg>
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
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="nav-icon">
                                <path fill-rule="evenodd" d="M4.848 2.771A49.144 49.144 0 0112 2.25c2.43 0 4.817.178 7.152.52 1.978.292 3.348 2.024 3.348 3.97v6.02c0 1.946-1.37 3.678-3.348 3.97a48.901 48.901 0 01-3.476.383.39.39 0 00-.297.17l-2.755 4.133a.75.75 0 01-1.248 0l-2.755-4.133a.39.39 0 00-.297-.17 48.9 48.9 0 01-3.476-.384c-1.978-.29-3.348-2.024-3.348-3.97V6.741c0-1.946 1.37-3.68 3.348-3.97zM6.75 8.25a.75.75 0 01.75-.75h9a.75.75 0 010 1.5h-9a.75.75 0 01-.75-.75zm.75 2.25a.75.75 0 000 1.5H12a.75.75 0 000-1.5H7.5z" clip-rule="evenodd"/>
                            </svg>
                            <span v-if="!sidebarCollapsed" class="nav-text">Support Ticket</span>
                            <svg v-if="!sidebarCollapsed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="arrow" :class="{ rotated: openMenus.support }">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                            </svg>
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
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="nav-icon">
                                <path fill-rule="evenodd" d="M20.599 1.5c-.376 0-.743.111-1.055.32l-5.08 3.385a18.747 18.747 0 00-3.471 2.987 10.04 10.04 0 014.815 4.815 18.748 18.748 0 002.987-3.472l3.386-5.079A1.902 1.902 0 0020.599 1.5zm-8.3 14.025a18.76 18.76 0 001.896-1.207 8.026 8.026 0 00-4.513-4.513A18.75 18.75 0 008.475 11.7l-.278.5a5.26 5.26 0 013.601 3.602l.502-.278zM6.75 13.5A3.75 3.75 0 003 17.25a1.5 1.5 0 01-1.601 1.497.75.75 0 00-.7 1.123 5.25 5.25 0 009.8-2.62 3.75 3.75 0 00-3.75-3.75z" clip-rule="evenodd"/>
                            </svg>
                            <span v-if="!sidebarCollapsed" class="nav-text">Appearances</span>
                            <svg v-if="!sidebarCollapsed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="arrow" :class="{ rotated: openMenus.appearances }">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                            </svg>
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
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="nav-icon">
                                <path fill-rule="evenodd" d="M12 5.25c1.213 0 2.415.046 3.605.135a3.256 3.256 0 013.01 3.01c.044.583.077 1.17.1 1.759L17.03 8.47a.75.75 0 10-1.06 1.06l3 3a.75.75 0 001.06 0l3-3a.75.75 0 00-1.06-1.06l-1.752 1.751c-.023-.65-.06-1.296-.108-1.939a4.756 4.756 0 00-4.392-4.392 49.422 49.422 0 00-7.436 0A4.756 4.756 0 003.89 8.282c-.017.224-.032.447-.046.672a.75.75 0 101.497.092c.013-.217.028-.434.044-.651a3.256 3.256 0 013.01-3.01c1.19-.09 2.392-.135 3.605-.135zm-6.97 6.22a.75.75 0 00-1.06 0l-3 3a.75.75 0 101.06 1.06l1.752-1.751c.023.65.06 1.296.108 1.939a4.756 4.756 0 004.392 4.392 49.413 49.413 0 007.436 0 4.756 4.756 0 004.392-4.392c.017-.223.032-.447.046-.672a.75.75 0 00-1.497-.092c-.013.217-.028.434-.044.651a3.256 3.256 0 01-3.01 3.01 47.953 47.953 0 01-7.21 0 3.256 3.256 0 01-3.01-3.01 47.759 47.759 0 01-.1-1.759L6.97 15.53a.75.75 0 001.06-1.06l-3-3z" clip-rule="evenodd"/>
                            </svg>
                            <span v-if="!sidebarCollapsed" class="nav-text">Plugins</span>
                        </router-link>
                    </li>
                    
                    <!-- Reports -->
                    <li class="nav-item nav-dropdown" :class="{ open: openMenus.reports }">
                        <a href="#" class="nav-link" @click.prevent="toggleMenu('reports')">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="nav-icon">
                                <path fill-rule="evenodd" d="M3 2.25a.75.75 0 000 1.5v16.5h-.75a.75.75 0 000 1.5H15v-5.25a.75.75 0 011.5 0V21h3.75a.75.75 0 000-1.5H21V5.25a.75.75 0 000-1.5H3V2.25zm6 6a.75.75 0 00-1.5 0v7.5a.75.75 0 001.5 0v-7.5zm4-1.5a.75.75 0 00-.75.75v9a.75.75 0 001.5 0v-9a.75.75 0 00-.75-.75zm4 3a.75.75 0 00-.75.75v6a.75.75 0 001.5 0v-6a.75.75 0 00-.75-.75z" clip-rule="evenodd"/>
                            </svg>
                            <span v-if="!sidebarCollapsed" class="nav-text">Reports</span>
                            <svg v-if="!sidebarCollapsed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="arrow" :class="{ rotated: openMenus.reports }">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                        <ul v-if="openMenus.reports && !sidebarCollapsed" class="sub-menu">
                            <li><router-link to="/reports/tenants">Tenants Report</router-link></li>
                            <li><router-link to="/reports/revenue">Revenue Report</router-link></li>
                            <li><router-link to="/reports/subscriptions">Subscriptions Report</router-link></li>
                            <li><router-link to="/reports/plans">Plans Report</router-link></li>
                        </ul>
                    </li>
                    
                    <!-- Settings -->
                    <li class="nav-item nav-dropdown" :class="{ open: openMenus.settings }">
                        <a href="#" class="nav-link" @click.prevent="toggleMenu('settings')">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="nav-icon">
                                <path fill-rule="evenodd" d="M11.078 2.25c-.917 0-1.699.663-1.85 1.567L9.05 4.889c-.02.12-.115.26-.297.348a7.493 7.493 0 00-.986.57c-.166.115-.334.126-.45.083L6.3 5.508a1.875 1.875 0 00-2.282.819l-.922 1.597a1.875 1.875 0 00.432 2.385l.84.692c.095.078.17.229.154.43a7.598 7.598 0 000 1.139c.015.2-.059.352-.153.43l-.841.692a1.875 1.875 0 00-.432 2.385l.922 1.597a1.875 1.875 0 002.282.818l1.019-.382c.115-.043.283-.031.45.082.312.214.641.405.985.57.182.088.277.228.297.35l.178 1.071c.151.904.933 1.567 1.85 1.567h1.844c.916 0 1.699-.663 1.85-1.567l.178-1.072c.02-.12.114-.26.297-.349.344-.165.673-.356.985-.57.167-.114.335-.125.45-.082l1.02.382a1.875 1.875 0 002.28-.819l.923-1.597a1.875 1.875 0 00-.432-2.385l-.84-.692c-.095-.078-.17-.229-.154-.43a7.614 7.614 0 000-1.139c-.016-.2.059-.352.153-.43l.84-.692c.708-.582.891-1.59.433-2.385l-.922-1.597a1.875 1.875 0 00-2.282-.818l-1.02.382c-.114.043-.282.031-.449-.083a7.49 7.49 0 00-.985-.57c-.183-.087-.277-.227-.297-.348l-.179-1.072a1.875 1.875 0 00-1.85-1.567h-1.843zM12 15.75a3.75 3.75 0 100-7.5 3.75 3.75 0 000 7.5z" clip-rule="evenodd"/>
                            </svg>
                            <span v-if="!sidebarCollapsed" class="nav-text">Settings</span>
                            <svg v-if="!sidebarCollapsed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="arrow" :class="{ rotated: openMenus.settings }">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                            </svg>
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
                    
                    <!-- Admins -->
                    <li class="nav-item nav-dropdown" :class="{ open: openMenus.admins }">
                        <a href="#" class="nav-link" @click.prevent="toggleMenu('admins')">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="nav-icon">
                                <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0021.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 003.065 7.097l-.896 1.045a.75.75 0 101.091 1.03l1.043-.896a9.724 9.724 0 003.989 1.024 9.724 9.724 0 003.989-1.024l1.043.896a.75.75 0 101.091-1.03l-.896-1.045zM15.75 7.5a.75.75 0 00-1.5 0v2.25H12a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H18a.75.75 0 000-1.5h-2.25V7.5z" clip-rule="evenodd"/>
                            </svg>
                            <span v-if="!sidebarCollapsed" class="nav-text">Admins</span>
                            <svg v-if="!sidebarCollapsed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="arrow" :class="{ rotated: openMenus.admins }">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                        <ul v-if="openMenus.admins && !sidebarCollapsed" class="sub-menu">
                            <li><router-link to="/admins">All Admins</router-link></li>
                            <li><router-link to="/admins/create">Create Admin</router-link></li>
                        </ul>
                    </li>
                    
                    <!-- Users -->
                    <li class="nav-item nav-dropdown" :class="{ open: openMenus.users }">
                        <a href="#" class="nav-link" @click.prevent="toggleMenu('users')">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="nav-icon">
                                <path d="M4.5 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM14.25 8.625a3.375 3.375 0 116.75 0 3.375 3.375 0 01-6.75 0zM1.5 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM17.25 19.128l-.001.144a2.25 2.25 0 01-.233.96 10.088 10.088 0 005.06-1.01.75.75 0 00.42-.643 4.875 4.875 0 00-6.957-4.611 8.586 8.586 0 011.71 5.157v.003z"/>
                            </svg>
                            <span v-if="!sidebarCollapsed" class="nav-text">Users</span>
                            <svg v-if="!sidebarCollapsed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="arrow" :class="{ rotated: openMenus.users }">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                            </svg>
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
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="nav-icon">
                                <path d="M12 .75a8.25 8.25 0 00-4.135 15.39c.686.398 1.115 1.008 1.134 1.623a.75.75 0 00.577.706c.352.083.71.148 1.074.195.323.041.6-.218.6-.544v-4.661a6.714 6.714 0 01-.937-.171.75.75 0 11.374-1.453 5.261 5.261 0 002.626 0 .75.75 0 11.374 1.452 6.712 6.712 0 01-.937.172v4.66c0 .327.277.586.6.545.364-.047.722-.112 1.074-.195a.75.75 0 00.577-.706c.02-.615.448-1.225 1.134-1.623A8.25 8.25 0 0012 .75z"/>
                                <path fill-rule="evenodd" d="M9.013 19.9a.75.75 0 01.877-.597 11.319 11.319 0 004.22 0 .75.75 0 11.28 1.473 12.819 12.819 0 01-4.78 0 .75.75 0 01-.597-.876zM9.754 22.344a.75.75 0 01.824-.668 13.682 13.682 0 002.844 0 .75.75 0 11.156 1.492 15.156 15.156 0 01-3.156 0 .75.75 0 01-.668-.824z" clip-rule="evenodd"/>
                            </svg>
                            <span v-if="!sidebarCollapsed" class="nav-text">System</span>
                            <svg v-if="!sidebarCollapsed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="arrow" :class="{ rotated: openMenus.system }">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                            </svg>
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
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="nav-icon">
                                <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span v-if="!sidebarCollapsed" class="nav-text">Tenants</span>
                        </router-link>
                    </li>
                </ul>
            </nav>
            
            <!-- Collapse Button -->
            <button class="collapse-btn" @click="toggleSidebar">
                <svg v-if="sidebarCollapsed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="collapse-icon">
                    <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd"/>
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="collapse-icon">
                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd"/>
                </svg>
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
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="logout-icon">
                            <path fill-rule="evenodd" d="M3 4.25A2.25 2.25 0 015.25 2h5.5A2.25 2.25 0 0113 4.25v2a.75.75 0 01-1.5 0v-2a.75.75 0 00-.75-.75h-5.5a.75.75 0 00-.75.75v11.5c0 .414.336.75.75.75h5.5a.75.75 0 00.75-.75v-2a.75.75 0 011.5 0v2A2.25 2.25 0 0110.75 18h-5.5A2.25 2.25 0 013 15.75V4.25z" clip-rule="evenodd"/>
                            <path fill-rule="evenodd" d="M19 10a.75.75 0 00-.75-.75H8.704l1.048-.943a.75.75 0 10-1.004-1.114l-2.5 2.25a.75.75 0 000 1.114l2.5 2.25a.75.75 0 101.004-1.114l-1.048-.943h9.546A.75.75 0 0019 10z" clip-rule="evenodd"/>
                        </svg>
                        Logout
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
            admins: false,
            system: false,
            reports: false
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
    background: #7f1625;
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
    border: 2px solid #7f1625;
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
    position: relative;
}

.search-icon {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    width: 16px;
    height: 16px;
    color: rgba(255, 255, 255, 0.4);
}

.search-input {
    width: 100%;
    padding: 10px 10px 10px 35px;
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
    background: rgba(255, 255, 255, 0.1);
    color: white;
    border-left-color: white;
}

.sidebar-collapsed .nav-link {
    justify-content: center;
    padding: 12px;
}

.nav-icon {
    width: 20px;
    height: 20px;
    min-width: 20px;
    margin-right: 12px;
    flex-shrink: 0;
}

.sidebar-collapsed .nav-icon {
    margin-right: 0;
}

.nav-text {
    font-size: 14px;
    font-weight: 500;
}

.arrow {
    margin-left: auto;
    width: 16px;
    height: 16px;
    transition: transform 0.2s;
}

.arrow.rotated {
    transform: rotate(180deg);
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
    background: rgba(255, 255, 255, 0.1);
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
    background: rgba(255, 255, 255, 0.1);
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.collapse-btn:hover {
    background: rgba(255, 255, 255, 0.2);
}

.collapse-icon {
    width: 20px;
    height: 20px;
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
    background: #7f1625;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    transition: background 0.2s;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-logout:hover {
    background: #5f1019;
}

.logout-icon {
    width: 16px;
    height: 16px;
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
