<template>
    <div
        class="flex w-full max-w-[57vh] aspect-square mx-auto h-[100dvh] max-h-[100dvh] flex-col border-1 border-black"
        v-if="items.length !== 0"
        ref="swipeArea"
    >
        <div class="flex bg-white h-[10vh] shrink-0">
            <div class="border-1 border-black w-[10vh] shrink-0 relative opacity-50"></div>
            <div class="border-1 border-black grow relative" ref="upItem">
                <template v-if="items[0]">
                    <navigate-button @click="loadItem(items[0].id)" direction="up"></navigate-button>
                    <v-lazy-image
                        :src="items[0].image_src"
                        :src-placeholder="items[0].tiny_placeholder"
                        :alt="items[0].title"
                        class="w-full h-full object-cover object-bottom"
                        :key="items[0].image_src"
                    />
                </template>
                <template v-else>
                    <div class="w-full h-full flex items-center justify-center text-xl tall:text-3xl text-center">
                        you have reached the newest one <br />
                        good time to change direction
                    </div>
                </template>
            </div>
            <div class="border-1 border-black w-[10vh] shrink-0"></div>
        </div>
        <div class="bg-white grow relative w-full flex snap-x snap-mandatory overflow-hidden justify-center">
            <div class="border-1 border-black w-preview shrink-0 relative" ref="leftItem">
                <navigate-button @click="loadItem(items[1].id)" direction="left"></navigate-button>
                <v-lazy-image
                    :src="items[1].image_src"
                    :src-placeholder="items[1].tiny_placeholder"
                    :alt="items[1].title"
                    class="w-full h-full object-cover"
                    :key="items[1].image_src"
                />
            </div>
            <div class="border-1 border-black w-preview shrink-0 h-[80vh]">
                <v-lazy-image
                    @click="toggleDetail()"
                    :src="items[2].image_src"
                    :src-placeholder="items[2].tiny_placeholder"
                    :alt="items[2].title"
                    class="w-full h-full object-cover cursor-pointer"
                    :key="items[2].image_src"
                />
            </div>
            <div class="border-1 border-black w-preview shrink-0 relative" ref="rightItem">
                <navigate-button @click="loadItem(items[3].id)" direction="right"></navigate-button>
                <v-lazy-image
                    :src="items[3].image_src"
                    :src-placeholder="items[3].tiny_placeholder"
                    :alt="items[3].title"
                    class="w-full h-full object-cover"
                    :key="items[3].image_src"
                />
            </div>
        </div>
        <div class="flex bg-white h-[10vh] shrink-0">
            <div class="border-1 border-black w-[10vh] shrink-0 relative"></div>
            <div class="border-1 border-black grow relative" ref="downItem">
                <template v-if="items[4]">
                    <navigate-button @click="loadItem(items[4].id)" direction="down"></navigate-button>
                    <v-lazy-image
                        :src="items[4].image_src"
                        :src-placeholder="items[4].tiny_placeholder"
                        :alt="items[4].title"
                        class="w-full h-full object-cover object-top"
                        :key="items[4].image_src"
                    />
                </template>
                <template v-else>
                    <div class="w-full h-full flex items-center justify-center text-xl tall:text-3xl text-center">
                        you have reached the oldest one <br />
                        good time to change direction
                    </div>
                </template>
            </div>
            <div class="border-1 border-black w-[10vh] shrink-0 relative"></div>
        </div>
    </div>
    <div ref="overlay" class="fixed inset-0 bg-gray-800 z-10 pointer-events-none opacity-0"></div>
    <div v-if="isLoading" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800/50 text-white/40">
        <svg
            class="text-gray-300 animate-spin w-20 h-20"
            viewBox="0 0 64 64"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                d="M32 3C35.8083 3 39.5794 3.75011 43.0978 5.20749C46.6163 6.66488 49.8132 8.80101 52.5061 11.4939C55.199 14.1868 57.3351 17.3837 58.7925 20.9022C60.2499 24.4206 61 28.1917 61 32C61 35.8083 60.2499 39.5794 58.7925 43.0978C57.3351 46.6163 55.199 49.8132 52.5061 52.5061C49.8132 55.199 46.6163 57.3351 43.0978 58.7925C39.5794 60.2499 35.8083 61 32 61C28.1917 61 24.4206 60.2499 20.9022 58.7925C17.3837 57.3351 14.1868 55.199 11.4939 52.5061C8.801 49.8132 6.66487 46.6163 5.20749 43.0978C3.7501 39.5794 3 35.8083 3 32C3 28.1917 3.75011 24.4206 5.2075 20.9022C6.66489 17.3837 8.80101 14.1868 11.4939 11.4939C14.1868 8.80099 17.3838 6.66487 20.9022 5.20749C24.4206 3.7501 28.1917 3 32 3L32 3Z"
                stroke="currentColor"
                stroke-width="5"
                stroke-linecap="round"
                stroke-linejoin="round"
            ></path>
            <path
                d="M32 3C36.5778 3 41.0906 4.08374 45.1692 6.16256C49.2477 8.24138 52.7762 11.2562 55.466 14.9605C58.1558 18.6647 59.9304 22.9531 60.6448 27.4748C61.3591 31.9965 60.9928 36.6232 59.5759 40.9762"
                stroke="currentColor"
                stroke-width="5"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="text-gray-800/80"
            ></path>
        </svg>
    </div>
    <ItemDetail :visible="detailActive" @close="toggleDetail" :item="items[2]" />
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { getActiveLanguage, loadLanguageAsync } from 'laravel-vue-i18n'
import VLazyImage from 'v-lazy-image'
import ItemDetail from '../components/ItemDetail.vue'
import NavigateButton from '../components/NavigateButton.vue'
import { useStorage } from '@vueuse/core'
import { useSwipe } from '@vueuse/core'

const route = useRoute()
const locale = ref('en')

const items = ref([])
const isLoading = ref(true)
const detailActive = ref(false)
const viewedItemIds = useStorage('viewedItemIds', [], sessionStorage)

const apiUrl = '/api/items/'

const router = useRouter()
const swipeArea = ref(null)
const overlay = ref(null)

const upItem = ref(null)
const downItem = ref(null)
const leftItem = ref(null)
const rightItem = ref(null)

onMounted(async () => {
    const itemId = route.query.id ?? null
    // viewedItemIds.value = route.query.viewedItemIds ?? []
    // viewedItemIds.value = (route.query.viewedItemIds) ? route.query.viewedItemIds.split(',') : []
    init(itemId)
})

const { direction, isSwiping, lengthX, lengthY } = useSwipe(swipeArea, {
    passive: true,
    onSwipe(e) {
        const length = Math.sqrt(lengthX.value ** 2 + lengthY.value ** 2)
        overlay.value.style.opacity = Math.min(length / 300, 0.9)
        resetHiglightedItems()
        switch (direction.value) {
            case 'LEFT':
                rightItem.value.classList.add('z-30')
                break
            case 'RIGHT':
                leftItem.value.classList.add('z-30')
                break
            case 'DOWN':
                upItem.value.classList.add('z-30')
                break
            case 'UP':
                downItem.value.classList.add('z-30')
                break
        }
    },
    onSwipeEnd(e, direction) {
        if (Math.abs(lengthX.value) > 100 || Math.abs(lengthY.value) > 100) {
            switch (direction) {
                case 'LEFT':
                    if (items.value[3]) loadItem(items.value[3].id)
                    break
                case 'RIGHT':
                    if (items.value[1]) loadItem(items.value[1].id)
                    break
                case 'DOWN':
                    if (items.value[0]) loadItem(items.value[0].id)
                    break
                case 'UP':
                    if (items.value[4]) loadItem(items.value[4].id)
                    break
            }
        }
        overlay.value.style.opacity = 0
        resetHiglightedItems()
    },
})

const init = async (id = null) => {
    isLoading.value = true

    const response = await axios.get(
        apiUrl + `?exclude=${viewedItemIds.value.join(',')}` + (id !== null ? `&id=${id}` : '')
    )
    await processResponse(response)
    isLoading.value = false
}

const loadItem = async (id) => {
    isLoading.value = true
    const response = await axios.get(`${apiUrl}?id=${id}&exclude=${viewedItemIds.value.join(',')}`)
    processResponse(response)
    isLoading.value = false
}

const processResponse = async (response) => {
    items.value = response.data.data
    viewedItemIds.value.push(items.value[2].id)
    if (viewedItemIds.value.length > 20) {
        viewedItemIds.value.shift()
    }
}

const toggleDetail = () => {
    if (!detailActive.value) {
        incrementDetailCount(items.value[2].id)
    }
    detailActive.value = !detailActive.value
}

const loadImages = (imageSrcArray) => {
    const promises = imageSrcArray.map((src) => {
        return new Promise((resolve, reject) => {
            const img = new Image()
            img.src = src
            img.onload = resolve
            img.onerror = reject
        })
    })

    return Promise.all(promises)
}

const incrementDetailCount = async (id) => {
    const response = await axios.put(`/api/items/${id}/increment-detail-count`)
}

const resetHiglightedItems = () => {
    leftItem.value.classList.remove('z-30')
    rightItem.value.classList.remove('z-30')
    downItem.value.classList.remove('z-30')
    upItem.value.classList.remove('z-30')
}
</script>

<style scoped>
.w-preview {
    width: calc(100% - 20vh);
}
</style>
