@extends('themes.default1.layouts.master')
@section('content')


<style type="text/css">
    table { table-layout:fixed; word-break:break-all; word-wrap:break-word; }

    .more-text{
     display:none;
}
</style>
    <div class="box box-primary">
 <div class="box-header">
       @if (count($errors) > 0)
        <div class="alert alert-danger alert-dismissable">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(Session::has('success'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{Session::get('success')}}
        </div>
        @endif
        <!-- fail message -->
        @if(Session::has('fails'))
        <div class="alert alert-danger alert-dismissable">
            <i class="fa fa-ban"></i>
            <b>{{Lang::get('message.alert')}}!</b> {{Lang::get('message.failed')}}.
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{Session::get('fails')}}
        </div>
        @endif
        <div id="response"></div>
          <h4 class="box-title">Activity Log</h4>

 </div>

<div class="box-body">

  <div class="row">
          <div class="col-md-12">

	
         
                           
             <table id="activity-table" class="table display" cellspacing="0"  styleClass="borderless">
                     <button  value="" class="btn btn-danger btn-sm btn-alldell" id="bulk_delete"><i class="fa fa-trash">&nbsp;&nbsp;</i> Delete Selected</button><br /><br />
                     
                    <thead><tr>
                            <th class="no-sort" style="width:1px"><input type="checkbox" name="select_all" onchange="checking(this)"></th>
                            <th style="width:50px;">Module</th>
                            <th>Description/Event</th>
                             <th style="width:60px;">Name</th>   
                              <th style="width:30px;">Role</th>      
                            <!-- <th>Subject id</th> -->
                            <!-- <th>Subject type</th> -->
                                                                                                             
                             <th style="width:200px;">Previous</th>
                             <th style="width:200px;">Updated</th>
                              <th style="width:50px;">Date</th>
                        </tr></thead>

                   </table>
            
        

   
</div>
</div>
</div>
</div>
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<!--  <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script> -->
<script type="text/javascript">
     function readmore(){
                        var maxLength = 100;
                        $("#activity-table tbody tr td").each(function(){
                            var myStr = $(this).text();

                           console.log(myStr);
                            if($.trim(myStr).length > maxLength){
                                var newStr = myStr.substring(0, maxLength);
                                 $(this).empty().html(newStr);
                                var removedStr = myStr.substring(maxLength, $.trim(myStr).length);
                                $(this).append('<span class="more-text">' + removedStr + '</span>');
                                $(this).append(' <a href="javascript:void(0);" class="read-more">read more...</a>');
                            }
                          }); 
                         }
        $('#activity-table').DataTable({
             destroy: true,
            // "initComplete": function(settings, json) {
            //              readmore();
            // },
            processing: true,
            serverSide: true,
             stateSave: true,
              order: [[ 0, "desc" ]],
            ajax: '{!! route('get-activity') !!}',
            "oLanguage": {
                "sLengthMenu": "_MENU_ Records per page",
                "sSearch"    : "Search: ",
                "sProcessing": '<img id="blur-bg" class="backgroundfadein" style="top:40%;left:50%; width: 50px; height:50 px; display: block; position:    fixed;" src="{!! asset("lb-faveo/media/images/gifloader3.gif") !!}">'
            },
            columnDefs: [
                { 
                    targets: 'no-sort', 
                    orderable: false,
                    order: []
                }
            ],
            columns: [
                {data: 'checkbox', name: 'checkbox'},
                 {data: 'name', name: 'name'},
                {data: 'description', name: 'description'},
                {data: 'username', name: 'username'},
                 {data: 'role', name: 'role'},
                // {data: 'subject_id', name: 'subject_id'},
                // {data: 'subject_type', name: 'subject_type'},
                
                // {data: 'causer_type', name: 'causer_type'},
                {data: 'old', name: 'old'},
                 {data: 'new', name: 'new'},
                {data: 'created_at', name: 'created_at'}
            ],
            "fnDrawCallback": function( oSettings ) {
                $('.loader').css('display', 'none');
            },
            "fnPreDrawCallback": function(oSettings, json) {
                $('.loader').css('display', 'block');
            },
        });
    </script>
<script>
    $(document).on('click','#activity-table tbody tr td .read-more',function(){
        var text=$(this).siblings(".more-text").text().replace('read more...','');
        console.log(text)
        $(this).siblings(".more-text").html(text);
        $(this).siblings(".more-text").contents().unwrap();
        $(this).remove();
    });
    $(function () {
    $('[data-toggle="popover"]').popover()
    })
</script>
    <script>

       function checking(e){
              $('#activity-table').find("td input[type='checkbox']").prop('checked', $(e).prop('checked'));
         }
         

         $(document).on('click','#bulk_delete',function(){
          var id=[];
          if (confirm("Are you sure you want to delete this?"))
            {
                $('.activity:checked').each(function(){
                  id.push($(this).val())
                });
                if(id.length >0)
                { 
                   $.ajax({
                          url:"{!! route('activity-delete') !!}",
                          method:"get",
                          data: $('#check:checked').serialize(),
                          beforeSend: function () {
                    $('#gif').show();
                    },
                    success: function (data) {
                    $('#gif').hide();
                    $('#response').html(data);
                    location.reload();
                    }
                   })
                }
                else
                {
                    alert("Please select at least one checkbox");
                }
            }  

         });



       
    </script>





@stop














