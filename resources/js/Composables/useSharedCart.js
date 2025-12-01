import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

export function useSharedCart() {
  const page = usePage()

  const cartItems = computed(() => page.props.cartItems || [])
  const ItemsCount = computed(() => page.props.cartItems.total_qty || null)

  return {
    cartItems,
    ItemsCount
  }
}

