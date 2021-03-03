<template>
    <div class="api-calling container mt-5">
        <h1>Create Product</h1>
        <div class="alert alert-danger alert-dismissible" role="alert" v-if="error">
           <b>{{ error.message }}</b>
           <ul>
               <li v-for="(errorName, index) in error.errors" :key="index">
                   {{ errorName[0] }}
               </li>
           </ul>
           <button type="button" class="close" @click="error = null">
               <span aria-hidden="true">&times;</span>
           </button>
       </div>
        <div class="form-group">
            <label>Name</label>
            <input v-model="product.name" type="text" class="form-control" placeholder="Name...">
        </div>
        <div class="form-group">
            <label>Price</label>
            <input v-model="product.price" type="text" class="form-control" placeholder="Price...">
        </div>
        <button class="btn btn-primary" @click="createProduct">Create</button>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                product: {
                    name: '',
                    price: ''
                },
                error: null
            }
        },
        methods: {
            async createProduct() {
                try {
                    const response = await axios.post('/products', {
                      name: this.product.name,
                      price: this.product.pric
                    });
                    console.log(response.data.product);
                } catch (error) {
                    this.error = error.response.data
                }
            }
        }
    }
</script>