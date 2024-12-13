<script>
export default {
    name: "Pagination",
    props: {
        currentPage: {
            type: Number,
            default: 1
        },
        totalCount: {
            type: Number,
            default: 0
        },
        numDisplayedPages: {
            type: Number,
            default: 10
        },
        numPerPage: {
            type: Number,
            default: 10
        }
    },
    computed: {
        totalPages() {
            return Math.ceil(this.totalCount / this.numPerPage);
        },
        pageBatch() {
            return Math.ceil(this.currentPage / this.numDisplayedPages);
        },
        pageRange() {
            let start = ((this.pageBatch - 1) * this.numDisplayedPages) + 1,
                finish = Math.min((this.pageBatch * this.numDisplayedPages), this.totalPages);

            return Array.from({length: finish - start + 1}, (_, a) => a + start);
        }
    }
}
</script>

<template>
    <div class="container-fluid m-0 p-0">
        <nav>
            <ul class="pagination justify-content-end">
                <li class="page-item">
                    <button class="page-link" @click="$emit('pageChange', Math.max(1, (currentPage - 1)))">Previous</button>
                </li>
                <li v-for="page in pageRange" class="page-item">
                    <button class="page-link" @click="$emit('pageChange', page)">{{ page }}</button>
                </li>
                <li class="page-item">
                    <button class="page-link" @click="$emit('pageChange', Math.min(totalPages, (currentPage + 1)))">Next</button>
                </li>
            </ul>
        </nav>
    </div>
</template>

<style scoped>

</style>
