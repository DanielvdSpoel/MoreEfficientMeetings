<template>
    <div>
        <Notifications/>
        <Navbar/>
        <div class="m-8">
            <slot name="content"/>
        </div>
    </div>
</template>

<script>
import Navbar from "../components/Navbar.vue";
import Notifications from "@/components/Notifications.vue";
export default {
name: "BaseLayout",
    components: {Notifications, Navbar}
}
</script>

<style scoped>

</style>
