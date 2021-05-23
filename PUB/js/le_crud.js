le_crud = {

    rem: function(id,el_id)
    {
        u = window.location.href;
        el = document.querySelector(el_id);
        if (typeof el !=='object') return false;

        data = {id:id,method:"remove_it"};

        if(!confirm("Удалить статью?")) return false;


        fetch(u, 
            {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {'Content-Type': 'application/json'}
            }
        ).then ((resp)=>{return resp.json()}).then
        ((resp)=>{
            if (!resp.success) {alert('Произошла ошибка!'); return false;}
            el.remove();
            });

        


    }




}