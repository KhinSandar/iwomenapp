<div class="table-responsive">
    <table id="data-table-command" class="table table-striped table-vmiddle">
    <thead>
        <th data-column-id="id" data-visible="false">Id</th>
	    <th data-column-id="UserId" data-visible="false">UserId</th>
	    <th data-column-id="Objectid" data-visible="false">Objectid</th>
		<th data-column-id="Title">Title</th>
		<th data-column-id="Titlemm">Title Mm</th>
		<th data-column-id="descriptioin">Description</th>
		<th data-column-id="descriptionmm">Description Mm</th>
		<th data-column-id="location">Location</th>
		<th data-column-id="locationmm">Location Mm</th>
		<th data-column-id="startdate">Start Date</th>
		<th data-column-id="enddate">End Date</th>
		<th data-column-id="starttime">Start Time</th>
		<th data-column-id="endtime">End Time</th>
        <th data-column-id="commands" data-formatter="commands" data-sortable="false">Action</th>
    </thead>
    <tbody>
    @foreach($calendars as $calendar)
        <tr>
            <td>{!! $calendar->id !!}</td>
            <td>{!! $calendar->userId !!}</td>
            <td>{!! $calendar->objectId !!}</td>
			<td>{!! $calendar->title !!}</td>
			<td>{!! $calendar->title_mm !!}</td>
			<td>{!! $calendar->description !!}</td>
			<td>{!! $calendar->description_mm !!}</td>
			<td>{!! $calendar->location !!}</td>
			<td>{!! $calendar->location_mm !!}</td>
			<td>{!! $calendar->start_date !!}</td>
			<td>{!! $calendar->end_date !!}</td>
			<td>{!! $calendar->start_time !!}</td>
			<td>{!! $calendar->end_time !!}</td>
            <td>
                <a href="{!! route('calendars.edit', [$calendar->id]) !!}"><i class="glyphicon glyphicon-edit"></i></a>
                <a href="{!! route('calendars.delete', [$calendar->id]) !!}" onclick="return confirm('Are you sure wants to delete this Calendar?')"><i class="glyphicon glyphicon-remove"></i></a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

</div>