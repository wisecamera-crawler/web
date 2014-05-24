/**
 * @fileoverview This file contains all the js needed for the navigation bar
 */


toggleSubNavBar();


/**
 * This function is used to toggle the height of the sub navigation bar(
 * the second bar with links to the log pages), it is called when the user
 * clicks on 相關紀錄查詢.
 * @return {boolean} false, this is to prevent default behaviour.
 */
function toggleSubNavBar()
{
  $('.subnavbar').animate({
    height: 'toggle',
    opacity: 'toggle'
  });

  return false;
}
