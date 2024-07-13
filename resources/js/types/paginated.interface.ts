import PaginationMetaInterface from "@/types/pagination-meta.interface";

interface PaginatedInterface<T> {
    items: T[]
    meta: PaginationMetaInterface
}

export default PaginatedInterface
