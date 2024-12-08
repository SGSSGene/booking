let page1 = document.getElementById('page1');
let page2 = document.getElementById('page2');
let page3 = document.getElementById('page3');
document.getElementById('page1forward').onclick=function() {
    page1.setAttribute("hidden", "");
    page2.removeAttribute("hidden");
};
document.getElementById('page2backward').onclick=function() {
    page2.setAttribute("hidden", "");
    page1.removeAttribute("hidden");
};
document.getElementById('page2forward').onclick=function() {
    page2.setAttribute("hidden", "");
    page3.removeAttribute("hidden");
    // fill all elements of final form
    let transferValue = (srcId, dstId) => {
        let srcTag = document.getElementById(srcId);
        let dstTag = document.getElementById(dstId);
        dstTag.value = srcTag.value;
    };
    transferValue("lvkennung", "lvkennung_check");
    transferValue("lvnbr", "lvnbr_check");
    transferValue("lvtype", "lvtype_check");
    transferValue("lvsemester", "lvsemester_check");
    transferValue("lvformat", "lvformat_check");
    transferValue("lvdate", "lvdate_check");
    transferValue("lvduration", "lvduration_check");
    transferValue("lvattendees", "lvattendees_check");

};
document.getElementById('page3backward').onclick=function() {
    page3.setAttribute("hidden", "");
    page2.removeAttribute("hidden");
};
document.getElementById('page3forward').onclick=function() {
    page3.setAttribute("hidden", "");
};

let showMonth = {
    year: 2024,
    month: 12
};

let lvduration = document.getElementById('lvduration');
let lvattendees = document.getElementById('lvattendees');

let fetchSlotData = function() {
    let duration = lvduration.value;
    let capacity = lvattendees.value;
    let year = showMonth.year;
    let month = showMonth.month;
    fetch(`rest_available_slots.php?year=${year}&month=${month}&duration=${duration}&capacity=${capacity}`)
      .then((response) => response.json())
      .then((json) => {
        if (duration != lvduration.value) return;
        if (capacity != lvattendees.value) return;
        if (showMonth.year != year) return;
        if (showMonth.month != month) return;
        let allMonths = document.getElementsByClassName("month-cur");
        for (let e of allMonths) {
            e.removeAttribute("vacant");
            e.children[1].innerHTML = "";
            e.children[1].setAttribute("hidden", "");
        }
        console.log(json);
        for (let e of json) {
            let day = e.start_time.slice(8, 10);
            let time = e.start_time.slice(11, 16);
            let tag = document.getElementsByClassName(`day-${day}`)[0];
            tag.setAttribute("vacant", "");
            let divTag = document.createElement("div");
            divTag.innerHTML = time;
            divTag.onclick = function() {
                let dateTag = document.getElementById("lvdate")
                dateTag.value = `${showMonth.year}-${showMonth.month}-${day} ${time}`;
            };
            tag.children[1].appendChild(divTag);
        }
      });
}
lvduration.onkeyup = fetchSlotData;
lvattendees.onkeyup = fetchSlotData;



let refreshMonth = function() {
    let calTitle = document.getElementById('cal-title');
    let dayTag = document.getElementById('days');
    dayTag.innerHTML = "";
    let date = new Date(`${showMonth.year}-${showMonth.month.toString().padStart(2, '0')}`);
    let months = ["", "Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember"];
    calTitle.innerHTML = `${months[showMonth.month]} ${showMonth.year}`;
    let dayOfWeek = (date.getDay()+6)%7; // Mon-Sun: 0-6
    date.setDate(date.getDate() - dayOfWeek);
    for (let i = 0; i < dayOfWeek; ++i) {
        let dayOfMonth = date.getDate();
        let liTag = document.createElement("li");
        liTag.setAttribute("class", "month-prev");
        liTag.innerHTML = `${dayOfMonth}`;
        dayTag.appendChild(liTag);
        date.setDate(date.getDate() +1);
    }
    while (date.getMonth()+1 == showMonth.month) {
        let dayOfMonth = date.getDate();
        let liTag = document.createElement("li");
        liTag.setAttribute("class", "month-cur");
        liTag.classList.add(`day-${dayOfMonth.toString().padStart(2, '0')}`);
        liTag.innerHTML = `<div>${dayOfMonth}</div><div></div>`;
        liTag.onclick = function() {
            let isHidden = liTag.children[1].hasAttribute("hidden");

            for (let e of document.getElementsByClassName("month-cur")) {
                e.children[1].setAttribute("hidden", "");
            }
            if (isHidden) {
                liTag.children[1].removeAttribute("hidden");
            } else {
                liTag.children[1].setAttribute("hidden", "");
            }
        };
        dayTag.appendChild(liTag);
        date.setDate(date.getDate() +1);
    }
    while (date.getDay() != 1) {
        let dayOfMonth = date.getDate();

        let liTag = document.createElement("li");
        liTag.setAttribute("class", "month-next");
        liTag.innerHTML = `${dayOfMonth}`;
        dayTag.appendChild(liTag);
        date.setDate(date.getDate() +1);
    }
    fetchSlotData();
}
refreshMonth();
document.getElementById('prev_month').onclick=function() {
   showMonth.month -= 1;
   if (showMonth.month == 0) {
       showMonth.year -= 1;
       showMonth.month = 12;
   }
   refreshMonth();
};
document.getElementById('next_month').onclick=function() {
   showMonth.month += 1;
   if (showMonth.month == 13) {
       showMonth.year += 1;
       showMonth.month = 1;
   }
   refreshMonth();
};
