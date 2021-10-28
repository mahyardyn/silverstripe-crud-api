
# Silverstripe CRUD REST API (Tip)
 This is REST API created with Silverstripe 4.9

# Requirements
 PHP 7.4 <br>
 Composer
 
# HTTP Methods
Available HTTP methods on a resource


<table>
<thead>
<tr>
<th><strong>Verb</strong></th>
<th><strong>Path</strong></th>
<th><strong>Action</strong></th>
<th><strong>Route Name</strong></th>
<th><strong>Description</strong></th>
</tr>
</thead>
<tbody>
    
<tr>
<td>GET</td>
<td>/Tips</td>
<td>index</td>
<td>Tips.index</td>
<td>Get all Tips</td>
</tr>
    
<tr>
<td>GET</td>
<td>/Tips/{Tip}</td>
<td>show</td>
<td>Tips.show</td>
<td>Get data of specific Tip</td>
</tr>

    
<tr>
<td>POST</td>
<td>/Tips</td>
<td>store</td>
<td>Tips.store</td>
<td>Create a new Tip</td>
</tr>


    
<tr>
<td>PUT</td>
<td>/Tips/{Tip}</td>
<td>update</td>
<td>Tips.update</td>
<td>Update a specific Tips</td>
</tr>
    
<tr>
<td>DELETE</td>
<td>/Tips/{Tip}</td>
<td>destroy</td>
<td>Tips.destroy</td>
<td>Delete a specific Tip</td>
</tr>
</tbody>
</table>

# JSON Api Specification
Let’s go over the structure of a JSON file in this repository
<pre>
{
    "data": {
        "Guid": 1,
        "title": "1st Title",
        "description": "this is test description",
        "created_at": "2021-10-20T12:53:24.000000Z",
        "updated_at": "2021-10-20T12:59:00.000000Z"
    }
}
</pre>




