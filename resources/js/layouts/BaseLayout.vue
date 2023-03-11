<template>
    <div>
        <Navbar/>
        <div class="m-8">
            <slot/>
        </div>
    </div>
</template>

<script>
import Navbar from "../components/Navbar.vue";
export default {
name: "BaseLayout",
    components: {Navbar}
}
</script>

<style scoped>

</style>
