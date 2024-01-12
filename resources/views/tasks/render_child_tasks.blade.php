 <?php
foreach ($arTasks as $key => $value) {
 
    if ($value['priority'] == 'hight') {
        $priority = 'badge-soft-danger';
    } elseif ($value['priority'] == 'medium') {
        $priority = 'badge-soft-info';
    } else {
        $priority = 'badge-soft-success';
    }

    if ($value['status'] == 'complete') {
        $classStatus = 'complete-task';
        $priority = '';
    } else {
        $classStatus = '';
    }
    
    

    ?>
 <tr id="child_{{ $value['id'] }}" class="child_tasks_<?php echo $value['parent_id']; ?>">

     <td style="">
         <span class="ms-0" style="padding-left: 50px">
             {!! $value['hasChildren']
                 ? '<i class="fe-chevron-right" onclick="showChildTasks(this, ' .
                     "'" .
                     $value['id'] .
                     "'" .
                     ')" id="' .
                     $value['id'] .
                     '"></i>'
                 : '' !!}
         </span>
         <label class="form-check-label <?php echo $classStatus; ?>" for="tasktodayCheck01">#<?php echo $value['id']; ?></label>
     </td>
     <td>
         <a class="<?php echo $classStatus; ?>" href="{{ route('task.detail', $value['id']) }}"><?php echo $value['name']; ?></a>
     </td>
     <td>
         <div>
             <img src="/assets/images/users/avatar-2.jpg" alt="image" class="avatar-sm img-thumbnail rounded-circle"
                 title="Houston Fritz" />
             <a class="<?php echo $classStatus; ?>"
                 href="{{ route('user.detail', $value['tasks_assign_to']['id']) }}"><?php echo $value['tasks_assign_to']['name']; ?></a>
         </div>
     </td>
     <td>
         <span class="<?php echo $classStatus; ?>"><?php echo \Illuminate\Support\Carbon::parse($value['due_date'])->format('d/m/Y H:i'); ?></span>
     </td>
     <td><span class="badge <?php echo $priority; ?> p-1 <?php echo $classStatus; ?>"><?php echo $value['priority']; ?></span>
     </td>
     <td>
         <span class="<?php echo $classStatus; ?>">
             <?php echo $value['status']; ?>
         </span>
     </td>
     <td>
         <ul class="list-inline table-action m-0">
             <li class="list-inline-item">
                 <a href="{{ route('task.edit', $value['id']) }}" class="action-icon px-1"> <i
                         class="mdi mdi-square-edit-outline"></i></a>
             </li>
             <li class="list-inline-item">
                 <div class="dropdown">
                     <a class="action-icon px-1 dropdown-toggle" href="#" data-bs-toggle="dropdown"
                         aria-haspopup="true" aria-expanded="false">
                         <i class="mdi mdi-dots-vertical"></i>
                     </a>

                     <div class="dropdown-menu dropdown-menu-end">
                         <a class="dropdown-item" href="#">Action</a>
                         <a class="dropdown-item" href="#">Another action</a>
                         <a class="dropdown-item" href="#">Something else
                             here</a>
                     </div>
                 </div>
             </li>
         </ul>
     </td>

 </tr>
 <?php
}
?>
