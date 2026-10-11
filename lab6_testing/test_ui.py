from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

def run_tests():
    # Запускаємо браузер Chrome
    driver = webdriver.Chrome()
    wait = WebDriverWait(driver, 10) # Явне очікування до 10 секунд

    try:
        print("Тест 1: Успішна авторизація (Позитивний сценарій)...")
        driver.get("https://the-internet.herokuapp.com/login")
        driver.find_element(By.ID, "username").send_keys("tomsmith")
        driver.find_element(By.ID, "password").send_keys("SuperSecretPassword!")
        driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
        # Перевірка: чи з'явилося повідомлення про успіх
        success_msg = wait.until(EC.presence_of_element_located((By.ID, "flash"))).text
        assert "You logged into a secure area!" in success_msg
        print("✅ Тест 1 пройдено!")

        print("Тест 2: Невдала авторизація (БОНУС - Негативний сценарій)...")
        driver.get("https://the-internet.herokuapp.com/login")
        driver.find_element(By.ID, "username").send_keys("wrong_user")
        driver.find_element(By.ID, "password").send_keys("wrong_pass")
        driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
        # Перевірка: чи з'явилася помилка
        error_msg = wait.until(EC.presence_of_element_located((By.ID, "flash"))).text
        assert "Your username is invalid!" in error_msg
        print("✅ Тест 2 пройдено (Бонус)!")

        print("Тест 3: Взаємодія з чекбоксами...")
        driver.get("https://the-internet.herokuapp.com/checkboxes")
        checkboxes = wait.until(EC.presence_of_all_elements_located((By.CSS_SELECTOR, "input[type='checkbox']")))
        # Якщо перший чекбокс не вибраний - клікаємо по ньому
        if not checkboxes[0].is_selected():
            checkboxes[0].click()
        # Перевірка: чи став чекбокс вибраним
        assert checkboxes[0].is_selected()
        print("✅ Тест 3 пройдено!")

    finally:
        driver.quit() # Закриваємо браузер
        print("🎉 Всі тести завершено успішно!")

if __name__ == "__main__":
    run_tests()