export const formatDate = (v) => (v ? String(v).slice(0, 10) : '')
export const formatTwd = (n) => `$ ${Number(n ?? 0).toLocaleString()}`
export const formatAddress = (o) =>
    [o?.shipping_city, o?.shipping_district, o?.shipping_address_detail].filter(Boolean).join('')
