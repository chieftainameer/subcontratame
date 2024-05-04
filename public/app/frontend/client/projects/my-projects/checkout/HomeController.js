route = '/client/projects/my-projects';
function paid(project_id, amount){
    itemData = new FormData();
    itemData.append('project_id', project_id);
    itemData.append('amount', amount);

    //Axios Http Post Request
    Core.post(route + '/checkout', itemData).then(function(res){
      Core.showToast('success', res.data.message);
      setTimeout(() => {
        window.location = res.data.redirect;    
      }, 1500);
    }).catch(function(err){
      console.log(err);
    });
}