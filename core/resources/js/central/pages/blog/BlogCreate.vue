<template>
    <PlaceholderPage title="Add New Blog" endpoint="/api/central/v1/blogs" />
</template>

<script>
import PlaceholderPage from '../../components/PlaceholderPage.vue'

export default {
    name: 'BlogCreate',
    components: { PlaceholderPage }
}
</script>



