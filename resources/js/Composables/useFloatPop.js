import { ref, computed, watch } from 'vue';
import { useFloating, offset, flip, shift, size } from '@floating-ui/vue'

function useFloatPop(options = {}) {
    const {
        placement = 'bottom-start',
        offsetValue = 0,
        enableWidth = true
    } = options

    const reference = ref(null)
    const floating = ref(null)
    const currentContent = ref(null)

    const middleware = [
        offset(({ rects }) => ({
            mainAxis: -rects.reference.height + offsetValue,
            crossAxis: 0
        })),
        flip(),
        shift({ padding: 0 })
    ]

    if (enableWidth) {
        middleware.splice(1, 0, size({
            apply({ rects, elements }) {
                elements.floating.style.width = `${rects.reference.width}px`
            }
        }))
    }

    const { floatingStyles, update } = useFloating(reference, floating, {
        placement,
        middleware
    })

    const openHover = (event, content) => {
        
        reference.value = event.currentTarget
        currentContent.value = content
        console.log(currentContent.value);
        
        update()
    }

    const closeHover = () => {
        currentContent.value = null
        reference.value = null
    }

    // 點擊外部關閉
    const handleClickOutside = (event) => {
        if (floating.value && !floating.value.contains(event.target) &&
            !reference.value?.contains(event.target)) {
            closeHover()
        }
    }

    watch(() => currentContent.value, (val) => {
        if (val) {
            document.addEventListener('mousedown', handleClickOutside)
        } else {
            document.removeEventListener('mousedown', handleClickOutside)
        }
    })

    return {
        reference,
        floating,
        currentContent,
        floatingStyles,
        openHover,
        closeHover,
        update
    }
}

export { useFloatPop }