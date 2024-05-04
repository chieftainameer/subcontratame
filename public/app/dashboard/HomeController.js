Echo.channel('new-events')
    .listen('.MessageEvent', (e) => {
       console.log(e);
    });