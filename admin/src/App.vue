<template>
    <div id="app" class="fixed-nav">
        <transition name="fade" mode="out-in">
            <router-view/>
        </transition>
    </div>
</template>

<style>
    #app {
        height:100%;
        width:100%;
    }
</style>
