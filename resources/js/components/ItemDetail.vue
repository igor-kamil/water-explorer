<template>
    <div class="fixed inset-0 z-50 flex justify-center items-center" v-if="visible">
        <div class="bg-black opacity-70 absolute inset-0 cursor-zoom-out" @click="emit('close')" />
        <div class="h-[95vh] custom-width overflow-y-auto overflow-x-hidden p-4 flex items-center justify-center">
            <button
                class="absolute top-10 right-10 p-4 tall:p-8 text-white cursor-pointer rounded-full bg-gray-500/80 z-10 hover:bg-gray-500/100"
                @click="emit('close')"
            >
                <svg class="h-[32px] w-[32px] tall:w-[82px] tall:h-[82px] stroke-2 stroke-current [stroke-linecap:round]" viewBox="0 0 32 32">
                    <path d="M25 7L7 25" />
                    <path d="M25 25L7 7" />
                </svg>
            </button>
            <div class="h-full flex flex-col relative w-full bg-white rounded-xl">
                <div class="bg-gray-300 h-full rounded-t-xl overflow-hidden">
                    <img :src="item.image_src" :alt="item.title" ref="zoom" class="w-full" />
                </div>
                <div class="px-4 py-4 tall:py-6 tall:px-6">
                    <h3 class="text-gray-500 tall:text-2xl mb-1" v-if="item.object">
                        {{ item.object }}
                    </h3>
                    <h2 class="text-xl tall:text-4xl font-bold mb-1">{{ item.title }}</h2>
                    <h3 class="text-gray-500 tall:text-2xl">
                        <span v-if="item.author">{{ item.author }}</span>
                        <span v-if="item.author && item.dating"> · </span>
                        <span v-if="item.dating">{{ item.dating }}</span>
                    </h3>
                    <div class="pb-4" v-if="item.description" v-html="item.description"></div>

                    <div class="flex p-2 md:p-4 border-black border-1 rounded-xl mt-4" v-if="item.web_url">
                        <img :src="qrCode" class="w-20 h-20 md:w-32 md:h-32" alt="open in collection online" />
                        <div class="grow">
                            <div class="flex h-full items-top">
                                <div class="ml-3 md:ml-4 tall:text-2xl">
                                    <span class="font-bold">Scannen Sie den Code, um das Objekt zu öffnen</span><br />
                                    <span>in MK&G Sammlung Online</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <ConfirmButton
                        class="bg-black text-white my-2 tall:my-4 text-lg tall:text-2xl px-4 w-full"
                        @click="emit('close')"
                        >Schließen</ConfirmButton
                    > -->
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onUnmounted, watch } from 'vue'
import PinchZoom from 'pinch-zoom-js'
import ConfirmButton from './ConfirmButton.vue'

let pz
const zoom = ref()

const props = defineProps({
    visible: Boolean,
    item: Object,
})
const emit = defineEmits(['close'])
const qrCode = computed(() => {
    return `/qrcode/${props.item.id}.svg`
})

onUnmounted(() => {
    pz.destroy()
})

watch(zoom, (newZoom) => {
    if (newZoom) {
        pz = new PinchZoom(newZoom, { minZoom: 0.9 })
    }
})
</script>

<style scoped>
.custom-width {
    width: calc(100vw - 5vh);
}
</style>
