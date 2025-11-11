import { ref, computed, watch } from 'vue';
import { useFloating, offset, flip, shift, size } from '@floating-ui/vue'

function useFloatPop(options = {}) {
    const {
        placement = 'bottom-start',
        offsetValue = 0,
        enableWidth = true,
        customWidth = null, // 新增：自定義固定寬度
        minWidth = null,    // 新增：最小寬度
        maxWidth = null     // 新增：最大寬度
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
                // 如果指定了 customWidth，使用固定寬度
                if (customWidth) {
                    elements.floating.style.width = `${customWidth}px`
                } else {
                    // 否則使用 reference 的寬度
                    let width = rects.reference.width
                    
                    // 應用最小/最大寬度限制
                    if (minWidth && width < minWidth) width = minWidth
                    if (maxWidth && width > maxWidth) width = maxWidth
                    
                    elements.floating.style.width = `${width}px`
                }
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
        // console.log(currentContent.value);
        // console.log(reference.value);        
        update()
    }
    
    const closeHover = () => {
        currentContent.value = null
        reference.value = null
    }
    
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