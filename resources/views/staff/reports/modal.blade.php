<!-- 
<div class="modal fade" id="addModal">
<div class="modal-dialog modal-lg">
<div class="modal-content p-4">

<form method="POST" action="{{ route('staff.reports.store') }}">
@csrf

<h5>Add Report</h5>


<div class="row mb-2">
    <div class="col">
        <input type="date" name="start_date" class="form-control" required>
    </div>
    <div class="col">
        <input type="date" name="end_date" class="form-control" required>
    </div>
</div class="table-responsive">
<textarea name="activity" class="form-control mb-2" placeholder="Activity" required></textarea>
<textarea name="details" class="form-control mb-2" placeholder="Details" required></textarea>
<input type="text" name="remarks" class="form-control mb-3" placeholder="Remarks">

<div class="text-end">
    <button class="btn btn-primary">Save</button>
</div>

</form>
</div>
</div>
</div> -->
