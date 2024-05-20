<template>
        <div class="flex w-full max-w-[57vh] aspect-square mx-auto h-[100dvh] max-h-[100dvh] flex-col border-1 border-black" v-if="items.length !== 0"  ref="swipeArea">
        <div class="flex bg-white h-[10vh] shrink-0">
            <div class="border-1 border-black w-[10vh] shrink-0 relative opacity-50"></div>
            <div class="border-1 border-black grow relative">
                <navigate-button @click="loadItem(items[0].id)" direction="up"></navigate-button>
                <v-lazy-image
                    :src="items[0].image_src"
                    :src-placeholder="items[0].tiny_placeholder"
                    :alt="items[0].title"
                    class="w-full h-full object-cover object-bottom"
                    :key="items[0].image_src"
                />
            </div>
            <div class="border-1 border-black w-[10vh] shrink-0"></div>
        </div>
        <div class=" bg-white grow relative w-full flex gap-6 snap-x snap-mandatory overflow-hidden justify-center">
            <div class="border-1 border-black w-preview shrink-0 relative">
                <navigate-button @click="loadItem(items[1].id)" direction="left"></navigate-button>
                <v-lazy-image
                    :src="items[1].image_src"
                    :src-placeholder="items[1].tiny_placeholder"
                    :alt="items[1].title"
                    class="w-full h-full object-cover"
                    :key="items[1].image_src"
                />
            </div>
            <div
                class="border-1 border-black w-preview shrink-0 h-[80vh]"
            >
                <v-lazy-image
                    @click="toggleDetail()"
                    :src="items[2].image_src"
                    :src-placeholder="items[2].tiny_placeholder"
                    :alt="items[2].title"
                    class="w-full h-full object-cover cursor-pointer"
                    :key="items[2].image_src"
                />
            </div>
            <div class="border-1 border-black w-preview shrink-0 relative">
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
            <div class="border-1 border-black grow relative">
                <navigate-button @click="loadItem(items[4].id)" direction="down"></navigate-button>
                <v-lazy-image
                    :src="items[4].image_src"
                    :src-placeholder="items[4].tiny_placeholder"
                    :alt="items[4].title"
                    class="w-full h-full object-cover object-top"
                    :key="items[4].image_src"
                />
            </div>
            <div class="border-1 border-black w-[10vh] shrink-0 relative"></div>
        </div>
    </div>
    <div v-if="isLoading" class="fixed inset-0 z-50 flex items-center justify-center bg-white/50">
        <div class="animate-spin rounded-full h-32 w-32 border-t-2 border-b-2 border-gray-500"></div>
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

const router = useRouter();
const swipeArea = ref(null);

onMounted(async () => {
    const itemId = route.query.id ?? null
    // viewedItemIds.value = route.query.viewedItemIds ?? []
    // viewedItemIds.value = (route.query.viewedItemIds) ? route.query.viewedItemIds.split(',') : []
    init(itemId)

})

const { direction, isSwiping, lengthX, lengthY } = useSwipe(
  swipeArea,
  {
    passive: true,
    onSwipe(e) {
    //   if (containerWidth.value) {
    //     if (lengthX.value < 0) {
    //       const length = Math.abs(lengthX.value)
    //   }
        // console.log(e, direction.value, lengthX.value, lengthY.value)
    },
    onSwipeEnd(e, direction ) {
        switch (direction) {
            case 'LEFT':
                loadItem(items.value[3].id)
                break;
            case 'RIGHT':
                loadItem(items.value[1].id)
                break;
            case 'DOWN':
                loadItem(items.value[0].id)
                break;
            case 'UP':
                loadItem(items.value[4].id)
                break;
        }
    },
  },
)

const init = async (id = null) => {
    isLoading.value = true

    const response = await axios.get(apiUrl + `?exclude=${viewedItemIds.value.join(',')}` + (id !== null ? `&id=${id}` : ''))
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

    // const items = response.data
    // differentItems.value = [items[0], items[2]]
    // similarItems.value = items[1]
    // await loadImages([
    //     ...similarItems.value.map((item) => item.image_src),
    //     differentItems.value[0][1].image_src,
    //     differentItems.value[0][1].image_src,
    // ])
    // loadImages([
    //     differentItems.value[0][0].image_src,
    //     differentItems.value[0][2].image_src,
    //     differentItems.value[1][0].image_src,
    //     differentItems.value[1][2].image_src,
    // ])
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

</script>

<style scoped>
.w-preview {
    width: calc(100% - 20vh);
}
</style>
