<template>
    <div class="flex w-full max-w-[57vh] aspect-square mx-auto h-[100dvh] max-h-[100dvh] flex-col border-1 border-black" v-if="items.length !== 0">
        <div class="flex bg-white h-[10vh] shrink-0">
            <div class="border-1 border-black w-[10vh] shrink-0 relative"></div>
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
        <div class="flex bg-white grow">
            <div class="border-1 border-black w-[10vh] shrink-0 relative">
                <navigate-button @click="loadItem(items[1].id)" direction="left"></navigate-button>
                <v-lazy-image
                    :src="items[1].image_src"
                    :src-placeholder="items[1].tiny_placeholder"
                    :alt="items[1].title"
                    class="w-full h-full object-cover object-right"
                    :key="items[1].image_src"
                />
            </div>
            <div
                class="border-1 border-black grow h-[80vh]"
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
            <div class="border-1 border-black w-[10vh] shrink-0 relative">
                <navigate-button @click="loadItem(items[3].id)" direction="right"></navigate-button>
                <v-lazy-image
                    :src="items[3].image_src"
                    :src-placeholder="items[3].tiny_placeholder"
                    :alt="items[3].title"
                    class="w-full h-full object-cover object-left"
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
    <InfoDetail :visible="infoActive" @close="toggleInfo" />
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { getActiveLanguage, loadLanguageAsync } from 'laravel-vue-i18n'
// import ImageDisplay from '../components/ImageDisplay.vue'
import VLazyImage from "v-lazy-image";
import ItemDetail from '../components/ItemDetail.vue'
import InfoDetail from '../components/InfoDetail.vue'
import NavigateButton from '../components/NavigateButton.vue'

const router = useRouter()
const route = useRoute()
const locale = ref('en')

const items = ref([])
// const similarItems = ref([])
// const differentItems = ref([])
const isLoading = ref(true)
const detailActive = ref(false)
const infoActive = ref(false)
const viewedItemIds = ref([])
// const activeItem = ref(1)

// const nextSimilar = ref(null)
// const prevSimilar = ref(null)
// const nextDifferent = ref([])
// const nextYounger = ref([])
// const nextOlder = ref([])

const apiUrl = '/api/items/'

onMounted(async () => {
    // apiUrl = useParam === 'digicult' ? '/api/items-digicult/' : '/api/items/'
    const itemId = route.query.id ?? null
    init(itemId)
})

const init = async (id = null) => {
    isLoading.value = true
    
    const response = await axios.get(apiUrl + (id !== null ? `?id=${id}` : ''))
    await processResponse(response)
    isLoading.value = false
    // loadNextSimilar()
    // loadPrevSimilar()
    // loadNextDifferent()
    // loadNextYounger()
    // loadNextOlder()
}

// const loadNextSimilar = async () => {
//     const id = nextSimilar.value ? nextSimilar.value.id : similarItems.value[activeItem.value + 1].id
//     const viewedItemIds = similarItems.value.map((item) => item.id).join(',')
//     const response = await axios.get(`/api/similar-item/${id}/?exclude=${viewedItemIds}`)
//     nextSimilar.value = response.data.data
//     // loadImages([nextSimilar.value.image_src])
// }

// const loadDifferent = async (id) => {
//     const viewedItemIds = similarItems.value.map((item) => item.id).join(',')
//     const response = await axios.get(`/api/different-items/${id}/?exclude=${viewedItemIds}`)
//     const items = response.data
//     differentItems.value = [items[0], items[1]]
// }

// const loadPrevSimilar = async () => {
//     const id = prevSimilar.value ? prevSimilar.value.id : similarItems.value[activeItem.value - 1].id
//     const viewedItemIds = similarItems.value.map((item) => item.id).join(',')
//     const response = await axios.get(`/api/similar-item/${id}/?exclude=${viewedItemIds}`)
//     prevSimilar.value = response.data.data
//     loadImages([prevSimilar.value.image_src])
// }

// const loadNextDifferent = async () => {
//     const id = similarItems.value[activeItem.value].id
//     const response = await axios.get(`/api/different-items/${id}/?exclude=${viewedItemIds}`)
//     nextDifferent.value = response.data
//     loadImages(nextDifferent.value.map((item) => item.image_src))
// }

// const loadNextYounger = async () => {
//     const id = similarItems.value[activeItem.value].id
//     const response = await axios.get(`/api/younger-items/${id}`)
//     nextYounger.value = response.data
//     loadImages(nextYounger.value.map((item) => item.image_src))
// }

// const loadNextOlder = async () => {
//     const id = similarItems.value[activeItem.value].id
//     const response = await axios.get(`/api/older-items/${id}`)
//     nextOlder.value = response.data
//     loadImages(nextOlder.value.map((item) => item.image_src))
// }

const loadItem = async (id) => {
    isLoading.value = true
    const response = await axios.get(`${apiUrl}?id=${id}&exclude=${viewedItemIds.value.join(',')}`)
    processResponse(response)
    isLoading.value = false
}

const processResponse = async (response) => {
    items.value = response.data.data
    // viewedItemIds.value.push(...items.value.map((item) => item.id))
    viewedItemIds.value.push(items.value[2].id)

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

const toggleInfo = () => {
    infoActive.value = !infoActive.value
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
