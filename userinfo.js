let user_name = sessionStorage.getItem("name");
let user_points = sessionStorage.getItem("points");
let user_correct_answers = sessionStorage.getItem("correctAnswers");

document.querySelector("span.name").innerHTML = user_name;
document.querySelector("span.points").innerHTML = user_points;
document.querySelector("span.correct").innerHTML = user_correct_answers;
