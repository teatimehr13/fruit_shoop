import { ref, computed, watch } from 'vue'

export function useHeroNavState({ isInHero, isScrollingDown }) {
  const isInHeroOverride = ref(null)
  const justLeftHero = ref(false)

  // 判斷有沒有剛離開
  watch(
    isInHero,
    (newVal, oldVal) => {
      if (oldVal === true && newVal === false) {
        justLeftHero.value = true
      }

      if (newVal === true) {
        justLeftHero.value = false
      }
    }
  )

  // 監聽滾動方向：不在 hero，又不是往下滑時
  watch(
    isScrollingDown,
    (down) => {
      if (!down && !isInHero.value) {
        justLeftHero.value = false
      }
    }
  )

  // 自動判斷的「hero 狀態」
  const autoHeroState = computed(() => {
    // 1. 還在 hero 裡
    if (isInHero.value) return true

    // 2. 剛離開 hero
    if (justLeftHero.value) return true

    // 3. 其他情況 
    return false
  })

  const isInHeroState = computed({
    get() {
      return isInHeroOverride.value ?? autoHeroState.value
    },
    set(val) {
      isInHeroOverride.value = val
    },
  })

  const resetHeroOverride = () => {
    isInHeroOverride.value = null
  }

  return {
    isInHeroState,
    isInHeroOverride,
    resetHeroOverride,
  }
}
