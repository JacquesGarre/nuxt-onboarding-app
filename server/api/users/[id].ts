export default defineEventHandler((event) => {

    switch(event.node.req.method){
        case 'PUT':
            return {
                hello: 'put'
            }
        case 'DELETE':
            return {
                hello: 'delete'
            }
    }
    
})