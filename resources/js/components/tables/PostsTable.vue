<script>
export default {
    name: "PostsTable",
    props: {
        posts: {
            type: Array,
            default() {
                return [];
            }
        }
    }
}
</script>

<template>
    <div class="container-fluid m-0 p-0">
        <table v-if="posts.length > 0" class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Title</th>
                    <th scope="col">Tags</th>
                    <th class="actions-column" scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(post, index) in posts">
                    <th scope="row">{{ post.id }}</th>
                    <td>{{ post.title }}</td>
                    <td>{{ (post.tags && post.tags.length > 0) ? post.tags.join(', ') : '' }}</td>
                    <td class="d-flex">
                        <button @click="$emit('edit', post)" class="btn btn-sm btn-info ms-auto me-3">Edit</button>
                        <button @click="$emit('delete', post)" class="btn btn-sm btn-danger">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <h3 v-else class="text-center p-5 text-body-secondary">
            There are no post(s) added yet.
        </h3>
    </div>
</template>

<style scoped>
  .actions-column {
      width: 150px;
  }
</style>
