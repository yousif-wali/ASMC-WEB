<style>
    table img{
        object-fit: contain;
        height:100px;
        aspect-ratio: 1/1;
    }
</style>
<h3>Foods</h3>
<button class="btn btn-success float-end mb-2" onclick="window.location = './AddFood'">Add</button>
    <table id="table" class="Table">
        <thead>
            <tr>
                <th>
                    Name
                </th>
                <th>
                    Description
                </th>
                <th>
                    Price
                </th>
                <th>
                    Images
                </th>
                <th>
                    Last Update
                </th>
                <th>
                    Actions
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $foods = new Food();
            $items = $foods->getFoods();
            foreach($items as $food){
                $id = $food[0];
                $title = $food[1];
                $desc = $food[2];
                $price = $food[3];
                $lastChange = $food[4];
                $image = $food[5];
                echo "
                <tr>
                    <td>
                    $title
                    </td>
                    <td>
                    $desc
                    </td>
                    <td>
                    $$price
                    </td>
                    <td>
                        <img src='./src/images/item/$image'/>
                    </td>
                    <td>
                    $lastChange UTC(+0)
                    </td>
                    <td>
                    <button class='btn btn-primary' onclick='window.location = `./EditFood/$title/$desc/$price/$id/$image`'><i class='bi bi-pencil-square'></i></button>
                    <button class='btn btn-danger' onclick='dropFood($id, `$title`)'><i class='bi bi-x'></i></button>
                    </td>
                </tr>
                ";
            }
            ?>
        </tbody>
    </table>
    <script>
        const dropFood = (id, title)=>{
            Swal.fire({
                title: `Do you want to Delete ${title}?`,
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: 'Delete',
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    fetch(`./DeleteFood/${id}`).then(res=>res.text()).then(res=>{});
                    Swal.fire(`${title} is deleted.`, '', 'success');
                    setTimeout(() => {
                        window.location.reload();                     
                    }, 1000);
                }
                })
        }
    </script>