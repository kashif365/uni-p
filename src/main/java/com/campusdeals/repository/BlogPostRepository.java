package com.campusdeals.repository;

import com.campusdeals.domain.BlogPost;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Modifying;
import org.springframework.data.jpa.repository.Query;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.Optional;

public interface BlogPostRepository extends JpaRepository<BlogPost, Long> {

    Page<BlogPost> findByPublishedTrueOrderByCreatedAtDesc(Pageable pageable);

    Page<BlogPost> findByPublishedTrueAndCategorySlugOrderByCreatedAtDesc(String categorySlug, Pageable pageable);

    List<BlogPost> findTop3ByPublishedTrueOrderByCreatedAtDesc();

    List<BlogPost> findTop3ByPublishedTrueAndIdNotOrderByCreatedAtDesc(Long id);

    Optional<BlogPost> findBySlugAndPublishedTrue(String slug);

    List<BlogPost> findAllByOrderByCreatedAtDesc();

    boolean existsBySlugAndIdNot(String slug, Long id);

    boolean existsBySlug(String slug);

    @Modifying
    @Transactional
    @Query("update BlogPost p set p.views = p.views + 1 where p.id = :id")
    void incrementViews(Long id);
}
