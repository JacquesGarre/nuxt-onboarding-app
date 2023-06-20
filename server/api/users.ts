export default defineEventHandler((event) => {

    

    switch(event.node.req.method){
        case 'GET':
            return {
                hello: 'get'
            }
        case 'POST':
            return event.node.req;
    }
    
})