package com.coupondeals.repository;

import com.coupondeals.domain.Store;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Modifying;
import org.springframework.data.jpa.repository.Query;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.Optional;

public interface StoreRepository extends JpaRepository<Store, Long> {

    List<Store> findAllByOrderBySortOrderAscNameAsc();

    List<Store> findByActiveTrueOrderByNameAsc();

    List<Store> findTop8ByActiveTrueAndFeaturedTrueOrderBySortOrderAsc();

    Optional<Store> findBySlugAndActiveTrue(String slug);

    boolean existsBySlugAndIdNot(String slug, Long id);

    boolean existsBySlug(String slug);

    long countByActiveTrue();

    @Query("select s from Store s where s.active = true and lower(s.name) like lower(concat('%', :q, '%')) order by s.name")
    Page<Store> search(String q, Pageable pageable);

    @Query("select s from Store s join s.categories c where c.id = :categoryId and s.active = true order by s.sortOrder, s.name")
    List<Store> findActiveInCategory(Long categoryId);

    @Modifying
    @Transactional
    @Query("update Store s set s.views = s.views + 1 where s.id = :id")
    void incrementViews(Long id);
}
