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
  const isInHero = ref(false)
  const lastScrollY = ref(0)

  let observer = null

  const handleScroll = () => {
    const current = window.scrollY
    isScrollingDown.value = current > lastScrollY.value && current > 100
    lastScrollY.value = current
  }

  const cleanupObserver = () => {
    if (observer) {
      observer.disconnect()
      observer = null
    }
  }

  const setupObserver = (el) => {
    if (!el) {
      isInHero.value = false
      cleanupObserver()
      return
    }

    cleanupObserver()

    observer = new IntersectionObserver(
      ([entry]) => {
        isInHero.value = entry.isIntersecting
      },
      {
        threshold: 0,
        rootMargin: '-64px 0px 0px 0px', // nav 高度
      }
    )

    observer.observe(el)
  }

  onMounted(() => {
    window.addEventListener('scroll', handleScroll, { passive: true })

    if (heroRef) {
      if (heroRef.value) {
        setupObserver(heroRef.value)
      } else {
        isInHero.value = false
      }

      watch(
        () => heroRef.value,
        (newEl) => {
          setupObserver(newEl)
        },
        { flush: 'post' }
      )
    } else {
      isInHero.value = false
    }
  })

  onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll)
    cleanupObserver()
  })

  return {
    isScrollingDown,
    isInHero,
  }
}