let myTable = document.getElementById("lstindex");
let listtr = myTable.querySelectorAll("tbody>tr");

/*OrderBy - VERSION FACTORISEE*/

		const compare = (ids, asc) => (row1, row2) => {
			const tdValue = (row, ids) => row.children[ids].textContent;
			const tri = (v1, v2) => v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2);
			return tri(tdValue(asc ? row1 : row2, ids), tdValue(asc ? row2 : row1, ids));
		};

		const tbody = document.querySelector('tbody');
		const thx = document.querySelectorAll('th');
		const trxb = tbody.querySelectorAll('tr');
		thx.forEach(th => th.addEventListener('click', () => {
			let classe = Array.from(trxb).sort(compare(Array.from(thx).indexOf(th), this.asc = !this.asc));
			classe.forEach(tr => tbody.appendChild(tr));
		}));
//end of OrderBy

        //checkbox filter
function checkboxFilter() { 
    let input, filter, table, tr, td, i,  value;
   
    input = document.getElementById("protection");
    filter = input.value;
    table = document.getElementById("lstindex");
    tr = table.getElementsByTagName("tr");
    
    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[5];
    if (td) {
        value = td.textContent || td.innerText;
        console.log(value);
    if ( input.checked && value=="Yes") { 
         tr[i].style.display = "none";
    } else {
        tr[i].style.display = "";
    }
    }
    }
    }
//end of checkbox filter


/******************Test part********************* */
//simple select filter
function filterTableBySelect() {
    // Variables
    let dropdown = document.getElementById("select_filter");
    let tr = myTable.getElementsByTagName("tr");
    let filter = dropdown.value;
  
    // Loops through rows and hides those with countries that don't match the filter
    for (let i = 0; i < tr.length; i++) { // `for...of` loops through the NodeList
      let td = tr[i].getElementsByTagName("td")[1];
      //let searched = td.innerText || td.textContent; // gets the 2nd `td` or nothing
      console.log(td);
      // if the filter is set to 'All', or this is the header row, or 2nd `td` text matches filter
     /* if (filter === "All" || !searched || (filter === searched.textContent)) {
        tr[i].style.display = ""; // shows this row
      }
      else {
        tr[i].style.display = "none"; // hides this row
      }*/
    }
  }

/**********Inused part************************* */

//multifilter (main menu)
/*function searchAll() {

    let input = document.getElementById("searchAll");
    let filter = input.value.toUpperCase();
    let tr = myTable.tBodies[0].getElementsByTagName("tr");
  
    // Loop through first tbody's rows
    for (let i = 0; i < tr.length; i++) {
  
      // define the row's cells
      let td = tr[i].getElementsByTagName("td");
  
      // hide the row
      tr[i].style.display = "none";
  
      // loop through row cells
      for (let j = 0; j < td.length; j++) {
  
        // if there's a match
        if (td[j].innerHTML.toUpperCase().indexOf(filter) > -1) {
  
          // show the row
          tr[i].style.display = "";
  
          // skip to the next row
          continue;
  
        }
      }
    }
  
  }*/
//end of multifilter




    //search in simple tables 
    /*function searchName() { 
        let input, filter, tr, td,  txtValue;
       
        input = document.getElementById("search");
        filter = input.value.toUpperCase();
        //table = document.getElementById("lstindex");
        tr = myTable.getElementsByTagName("tr");
        
        // Loop through all table rows, and hide those who don't match the search query
        for (let i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[0];
        if (td) {
          txtValue = td.textContent || td.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) { //NOTE: Remove toUpperCase() if you want to perform a case-sensitive search.
             tr[i].style.display = "";
        } else {
              tr[i].style.display = "none";
        }
        }
        }
        }

        function searchNote() { 
            let input, filter, tr, td, i,  txtValue;
           
            input = document.getElementById("searchNote");
            filter = input.value.toUpperCase();
            //table = document.getElementById("lstindex");
            tr = myTable.getElementsByTagName("tr");
            console.log(filter);
            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[1];
            if (td) {
              txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) { //NOTE: Remove toUpperCase() if you want to perform a case-sensitive search.
                 tr[i].style.display = "";
            } else {
                  tr[i].style.display = "none";
            }
            }
            }
            }


*/
//order
/************/
//https://www.pierre-giraud.com/trier-tableau-javascript/
/*
const tbody = document.querySelector('tbody');
const thx = document.querySelectorAll('th');
const trxb = tbody.querySelectorAll('tr');
const compare = function(ids, asc) {
    return function(row1, row2) {
        const tdValue = function(row, ids) {
            return row.children[ids].textContent;
        }
        const tri = function(v1, v2) {
            if (v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2)) {
                return v1 - v2;
            } else {
                return v1.toString().localeCompare(v2);
            }
            return v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2);
        };
        return tri(tdValue(asc ? row1 : row2, ids), tdValue(asc ? row2 : row1, ids));
    }
}


thx.forEach(function(th) {
    th.addEventListener('click', function() {
        let classe = Array.from(trxb).sort(compare(Array.from(thx).indexOf(th), this.asc = !this.asc));
        classe.forEach(function(tr) {
            tbody.appendChild(tr)
        });
    })
});*/


//filters
//1. filter: user in select
/*let selectUser = document.getElementById("lstusers");
selectUser.addEventListener("change", function() {
    listtr.forEach(tr => {
        tr.style.display = "none";
        if (tr.className.includes(selectUser.value))
            tr.style.display = "table-row";
    });
});
*/



//2. filter : status 
//v0
/*let listInput = document.getElementById('lststatus');
let listOption = listInput.querySelectorAll('option');
listInput.addEventListener("change", filterByKeyword);

function filterByKeyword(e) {
    listtr.forEach(tr => tr.style.display = "none");

    listOption.forEach(elt => {
        if (elt.selected) {
            listtr.forEach(tr => {
                if (tr.className.includes(elt.value))
                    tr.style.display = "table-row";
            });
        }
    });
}*/


//search base ->for the table with id
/*function searchFunctionId() { 
    let input, filter, table, tr, td, i,  txtValue;
   
    input = document.getElementById("search");
    filter = input.value.toUpperCase();
    table = document.getElementById("lstindex");
    tr = table.getElementsByTagName("tr");
    
    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[1]; //0=>id
    if (td) {
      txtValue = td.textContent || td.innerText;
    if (txtValue.toUpperCase().indexOf(filter) > -1) {
         tr[i].style.display = "";
    } else {
          tr[i].style.display = "none";
    }
    }
    }
}*/