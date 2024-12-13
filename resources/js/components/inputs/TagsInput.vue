<template>
    <div>
        <!-- Input for adding tags -->
        <div class="tags-input">
            <input
                type="text"
                class="form-control"
                v-model="newTag"
                @keydown.enter="addTag"
                @keydown.delete="removeLastTag"
                @input="searchTags"
                v-on:keydown.enter.prevent='addTag'
                placeholder="Add a tag..."
                list="tag-suggestions"
            />
        </div>

        <!-- Autocomplete suggestions -->
        <datalist id="tag-suggestions">
            <option v-for="suggestion in filteredTags" :key="suggestion" :value="suggestion"></option>
        </datalist>

        <!-- Display added tags -->
        <div class="tags-list">
      <span v-for="(tag, index) in tags" :key="index" class="tag">
        {{ tag }}
        <span @click="removeTag(index)" class="remove-tag">X</span>
      </span>
        </div>

        <!-- Hidden input field to submit tags -->
        <input type="hidden" :value="tags.join(',')" />
    </div>
</template>

<script>
export default {
    name: 'TagsInput',
    props: {
        tags: {
            type: Array,
            default() {
                return [];
            }
        }
    },
    data() {
        return {
            newTag: '',
            filteredTags: [],
        };
    },
    methods: {
        // Add a new tag to the list
        addTag() {
            const tag = this.newTag.trim();
            if (tag && !this.tags.includes(tag)) {
                this.tags.push(tag);
                this.newTag = '';  // Clear the input field
                this.filteredTags = []; // Clear suggestions
            }
        },

        // Remove a specific tag
        removeTag(index) {
            this.tags.splice(index, 1);
        },

        // Remove the last tag when delete key is pressed
        removeLastTag(event) {
            if (this.newTag === '') {
                this.tags.pop();
            }
        },

        // Search and filter tags based on user input
        async searchTags() {
            if (this.newTag.trim()) {
                try {
                    const response = await axios.get('/tags', {
                        params: { search: this.newTag }
                    });
                    this.filteredTags = response.data.tags;
                } catch (error) {
                    console.error('Error fetching tags:', error);
                }
            } else {
                this.filteredTags = [];
            }
        },
    }
};
</script>

<style scoped>
.tags-input {
    display: flex;
    align-items: center;
}

.tags-input input {
    margin-right: 10px;
    padding: 5px;
    border: 1px solid #ccc;
    width: 100%;
}

.tags-list {
    margin-top: 10px;
}

.tag {
    display: inline-block;
    background-color: #e0e0e0;
    padding: 5px 10px;
    margin: 5px;
    border-radius: 3px;
}

.remove-tag {
    margin-left: 10px;
    background-color: red;
    color: white;
    border: none;
    padding: 3px 7px;
    border-radius: 50%;
    cursor: pointer;
}
</style>
