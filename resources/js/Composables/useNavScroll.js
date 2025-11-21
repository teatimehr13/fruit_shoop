// composables/useNavScroll.js
import { ref, onMounted, onUnmounted, watch } from 'vue'

// //到了hero的區域變化
// export function useNavScroll(heroRef) {
//   const isScrollingDown = ref(false)
//   const isInHero = ref(false)
//   const lastScrollY = ref(0)
  
//   let observer = null

//   // 處理滾動方向
//   const handleScroll = () => {
//     const current = window.scrollY
//     isScrollingDown.value = current > lastScrollY.value && current > 100
//     lastScrollY.value = current
//   }

//   // 清理舊的 observer
//   const cleanupObserver = () => {
//     if (observer) {
//       observer.disconnect()
//       observer = null
//     }
//   }

//   // 設置 IntersectionObserver
//   const setupObserver = (el) => {
//     if (!el) {
//       isInHero.value = false
//       return
//     }

//     cleanupObserver()

//     observer = new IntersectionObserver(
//       ([entry]) => {
//         isInHero.value = entry.isIntersecting
//       },
//       {
//         threshold: 0,
//         rootMargin: '-64px 0px 0px 0px',
//       }
//     )

//     observer.observe(el)
//   }

//   onMounted(() => {
//     // 監聽滾動
//     window.addEventListener('scroll', handleScroll, { passive: true })

//     // 如果有提供 heroRef,才設置監聽
//     if (heroRef) {
//       // 立即檢查
//       if (heroRef.value) {
//         setupObserver(heroRef.value)
//       } else {
//         isInHero.value = false
//       }

//       // 監聽 heroRef 變化
//       watch(
//         () => heroRef.value,
//         (newEl) => {
//           setupObserver(newEl)
//         },
//         { flush: 'post' } // 確保在 DOM 更新後執行
//       )
//     } else {
//       // 沒有 hero 的頁面,直接設為 false
//       isInHero.value = false
//     }
//   })

//   onUnmounted(() => {
//     window.removeEventListener('scroll', handleScroll)
//     cleanupObserver()
//   })

//   return {
//     isScrollingDown,
//     isInHero,
//   }
// }

//最上層時變化
export function useNavScroll(heroRef) {
  const isScrollingDown = ref(false)
  const isInHero = ref(true) // 預設在最上面
  const lastScrollY = ref(0)

  // 處理滾動
  const handleScroll = () => {
    const current = window.scrollY
    
    // 判斷滾動方向
    isScrollingDown.value = current > lastScrollY.value && current > 100
    
    // 判斷是否在頂部(考慮 nav 高度)
    isInHero.value = current < 64 // 64px 是 nav 的高度
    
    lastScrollY.value = current
  }

  onMounted(() => {
    // 監聽滾動
    window.addEventListener('scroll', handleScroll, { passive: true })
    
    // 初始執行一次
    handleScroll()
  })

  onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll)
  })

  return {
    isScrollingDown,
    isInHero,
  }
}